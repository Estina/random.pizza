<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use AppBundle\Service\City;
use AppBundle\Service\Pizza;
use AppBundle\Service\Restaurant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function homeAction()
    {
        return $this->render('Home/index.html.twig');
    }

    /**
     * @Route("/cities/{countryCode}/{multi}")
     * @Method("GET")
     *
     * @param string $countryCode
     *
     * @return Response
     */
    public function citiesAction($countryCode, $multi = false)
    {
        /** @var City $cityService */
        $cityService = $this->get('service.city');
        $params = [
            'list' => $cityService->getCitiesByCountryCode($countryCode),
            'multi' => (bool) $multi
        ];

        return $this->render('Home/cities.html.twig', $params);
    }

    /**
     * @Route("/restaurants/{cityId}", requirements={"cityId" = "\d+"})
     * @Method("GET")
     *
     * @param int $cityId
     *
     * @return Response
     */
    public function restaurantsAction($cityId)
    {
        /** @var City $cityService */
        $cityService = $this->get('service.city');
        $params = [];
        try {
            $city = $cityService->get((int) $cityId);
            $params = ['list' => $city->getRestaurants()];
        } catch (\InvalidArgumentException $e) {
            // do nothing
        }

        return $this->render('Home/restaurants.html.twig', $params);
    }

    /**
     * @Route("/generate")
     * @Method("POST")
     */
    public function generateAction()
    {
        /** @var City $cityService */
        $cityService = $this->get('service.city');
        /** @var Pizza $pizzaService */
        $pizzaService = $this->get('service.pizza');
        /** @var Restaurant $restaurantService */
        $restaurantService = $this->get('service.restaurant');
        /** @var Request $request */
        $request = $this->get('request');

        $options = $this->getOptions($request);
        $city = $cityService->get($options['cityId']);
        if (0 === $options['restaurantId']) {
            $restaurant = $restaurantService->get($restaurantService->getRandomId($options['cityId']));
        } else {
            $restaurant = $restaurantService->get($options['restaurantId']);
        }

        $pizzas = $restaurantService->getPizzas($restaurant, $options);

        if (!$pizzas) {
            return new JsonResponse(['error' => 'No pizzas found. Try different settings.']);
        }

        $ids = $pizzaService->getRandomIds($pizzas, $options['qty']);
        $ids = array_count_values($ids);
        /** @var Entity\Result $result */
        $result = $pizzaService->save($city, $restaurant, $options);
        if ($result) {
            foreach ($ids as $pizzaId => $qty) {
                $pizzaService->addPizza($result->getId(), $pizzaId, $qty);
            }
        }

        return new JsonResponse(['href' => $result->getSlug()]);
    }

    /**
     * @Route("/{slug}", requirements={"slug" = "^[a-z0-9]{6}$"})
     * @Method("GET")
     */
    public function displayResultAction($slug)
    {
        /** @var Pizza $pizzaService */
        $pizzaService = $this->get('service.pizza');

        $result = $pizzaService->getResult($slug);
        if (!$result) {
            throw new NotFoundHttpException("Result not found");
        }

        $result['options'] = json_decode($result['options']);
        $result['pizzas'] = $pizzaService->getPizzas($result['result_id']);

        return $this->render('Result/index.html.twig', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/form")
     *
     * @return Response
     */
    public function formAction()
    {
        /** @var Request $request */
        $request = $this->get('request');
        /** @var Restaurant $restaurantService */
        $restaurantService = $this->get('service.restaurant');

        if ($request->isMethod('POST')) {
            $name = $request->get('restaurant');
            $cities = $this->getCities();
            if ($name && $cities) {
                $pizzas = $this->getPizzas();
                $restaurant = $restaurantService->save($name, $cities, $pizzas);
                $this->addFlash('saved', $restaurant->getName());
                $this->redirect($this->generateUrl('app_app_form'));
            }
        }

        return $this->render('Form/index.html.twig');
    }

    /**
     * @Route("/form/more/{lastIndex}")
     * @Method("GET")
     *
     * @param int $lastIndex
     *
     * @return Response
     */
    public function morePizzasAction($lastIndex)
    {
        $lastIndex = max(10, $lastIndex);
        if ($lastIndex > 190) {
            return new Response('');
        }

        return $this->render('Form/more_pizzas.html.twig', [
            'start' => ++$lastIndex,
            'stop' => ($lastIndex + 9)
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getOptions(Request $request)
    {
        $options = [
            'countryCode' => trim($request->get('countryCode')),
            'cityId' => (int) $request->get('cityId'),
            'restaurantId' => (int) $request->get('restaurantId'),
            'qty' => (int) $request->get('qty'),
            'meat' => (bool) $request->get('meat'),
            'fish' => (bool) $request->get('fish'),
            'vegetarian' => (bool) $request->get('vegetarian'),
            'hot' => (bool) $request->get('hot')
        ];

        $options['countryCode'] = strtolower($options['countryCode']);
        $options['countryCode'] = preg_replace('/[^a-z]/', '', $options['countryCode']);
        $options['countryCode'] = substr($options['countryCode'], 0, 2);

        $options['qty'] = max(1, $options['qty']);
        $options['qty'] = min(10, $options['qty']);

        return $options;
    }

    /**
     * @return array
     */
    private function getCities()
    {
        /** @var Request $request */
        $request = $this->get('request');
        /** @var City $cityService */
        $cityService = $this->get('service.city');

        if (!$request->get('city')) {
            throw new \InvalidArgumentException('No cities submitted');
        }

        $result = [];
        foreach ($request->get('city') as $cityId) {
            $result[] = $cityService->get($cityId);
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getPizzas()
    {
        /** @var Request $request */
        $request = $this->get('request');
        /** @var Pizza $pizzaService */
        $pizzaService = $this->get('service.pizza');

        $result = [];
        $post = $request->request->all();
        foreach ($post as $param => $value) {
            if (false !== strpos($param, 'pizza_')) {
                list($temp, $index) = explode('_', $param);
                $value = preg_replace('/[^A-Za-z0-9 \']/', '', $value);
                if (!empty($value)) {

                    $pizza = new Entity\Pizza();
                    $pizza->setName(trim($value));
                    $pizza->setMeat((bool) $request->get('meat_' . $index, false));
                    $pizza->setVegetarian((bool) $request->get('vegetarian_' . $index, false));
                    $pizza->setFish((bool) $request->get('fish_' . $index, false));
                    $pizza->setHot((bool) $request->get('hot_' . $index, false));
                    $pizza = $pizzaService->savePizza($pizza);

                    $result[] = $pizza;
                }
            }
        }

        if (!$result) {
            throw new \InvalidArgumentException('No pizzas submitted');
        }

        return $result;
    }


}
