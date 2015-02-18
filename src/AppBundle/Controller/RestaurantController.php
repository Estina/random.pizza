<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RestaurantController extends Controller
{
    /**
     * @Route("/restaurants/{cityId}")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction($cityId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:City');
        $city = $repository->find((int) $cityId);

        return $this->render('Home/restaurants.html.twig', array('list' => $city->getRestaurants()));
    }

}
