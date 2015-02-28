<?php

namespace AppBundle\Controller;

use AppBundle\Service\Generator;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/cities/{countryCode}")
     * @Method("GET")
     *
     * @param string $countryCode
     *
     * @return Response
     */
    public function citiesAction($countryCode)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:City');
        $list = $repository->findBy(['countryCode' => $countryCode]);

        return $this->render('Home/countries.html.twig', array('list' => $list));
    }

    /**
     * @Route("/restaurants/{cityId}")
     * @Method("GET")
     *
     * @param int $cityId
     *
     * @return Response
     */
    public function restaurantsAction($cityId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:City');
        $city = $repository->find((int) $cityId);

        return $this->render('Home/restaurants.html.twig', array('list' => $city->getRestaurants()));
    }

    /**
     * @Route("/generate")
     * @Method("POST")
     */
    public function generateAction()
    {
        /** @var Generator $generator */
        $generator = $this->get('service.generator');
        /** @var Request $request */
        $request = $this->get('request');

        return new Response($generator->generatePizzas($request));
    }

    /**
     * @Route("/generate")
     * @Method("POST")
     */
    public function displayResultAction($hash)
    {
        /** @var Generator $generator */
        $generator = $this->get('service.generator');
        /** @var Request $request */
        $request = $this->get('request');

        return new Response($generator->generatePizzas($request));
    }



}
