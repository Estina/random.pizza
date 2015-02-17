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
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('AppBundle:Product')->findAllByCountryCode($countryCode);
        $list = $this->getDoctrine()
            ->getRepository('AppBundle:City')
            ->findBy(['countryCode' => $countryCode]);

        return $this->render('Home/countries.html.twig', array('list' => $list));
    }

}
