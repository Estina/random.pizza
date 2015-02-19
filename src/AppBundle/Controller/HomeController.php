<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('Home/index.html.twig');
    }

    /**
     * @Route("/generate")
     * @Method("POST")
     */
    public function generateAction()
    {
        /** @var Request $request */
        $request = $this->get('request');

        return new Response($request->get('cityId'));
    }
}
