<?php

namespace AppBundle\Service;

use AppBundle\Entity\City;
use AppBundle\Entity\Restaurant;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class Generator
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function generatePizzas(Request $request)
    {
        $options = $this->getOptions($request);
        $city = $this->getCity($options['cityId']);
        $restaurant = $this->getRestaurant($options['restaurantId']);
        $pizzas = $restaurant->getPizzasFiltered(
            $options['meat'],
            $options['fish'],
            $options['vegetarian'],
            $options['hot']
        );

        if (!$pizzas) {
            throw new \InvalidArgumentException(
                sprintf('No pizzas found, options: ', print_r($options, true))
            );
        }

        $ids = $this->getRandomIds($pizzas, $options['qty']);
        $ids = array_count_values($ids);

        return print_r($ids, true);
    }

    /**
     * @param int $cityId
     *
     * @return City
     */
    private function getCity($cityId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\City')->find($cityId);
        if (!$result) {
            throw new \InvalidArgumentException('City was not found: ' . $cityId);
        }

        return $result;
    }

    /**
     * @param int $restaurantId
     *
     * @return Restaurant
     */
    private function getRestaurant($restaurantId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\Restaurant')->find($restaurantId);
        if (!$result) {
            throw new \InvalidArgumentException('Restaurant was not found: ' . $restaurantId);
        }

        return $result;
    }

    /**
     * @param array $pizzas
     * @param int   $qty
     *
     * @return array
     */
    private function getRandomIds($pizzas, $qty)
    {
        $ids = [];
        if (count($pizzas) > $qty) {
            shuffle($pizzas);
            $ids = array_slice($pizzas, 0, $qty);
        } else {
            while (count($ids) < $qty) {
                $key = array_rand($pizzas);
                $ids[] = $pizzas[$key];
            }
        }

        return $ids;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getOptions(Request $request)
    {
        $options = [
            'cityId' => (int) $request->get('cityId'),
            'restaurantId' => (int) $request->get('restaurantId'),
            'qty' => (int) $request->get('qty'),
            'meat' => (bool) $request->get('meat'),
            'fish' => (bool) $request->get('fish'),
            'vegetarian' => (bool) $request->get('vegetarian'),
            'hot' => (bool) $request->get('hot')
        ];

        $options['qty'] = max(1, $options['qty']);
        $options['qty'] = min(10, $options['qty']);

        return $options;
    }
}