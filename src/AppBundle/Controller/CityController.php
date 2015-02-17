<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    /**
     * @Route("/cities/{countryCode}")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction($countryCode)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('AppBundle:City')->findAllByCountryCode($countryCode);

        return $this->render('Home/countries.html.twig', array('list' => $list));
    }

}
