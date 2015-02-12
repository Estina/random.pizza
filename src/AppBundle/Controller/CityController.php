<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CityController extends Controller
{
    /**
     * @Route("/cities/{countryCode}")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function listAction($countryCode)
    {
        $list = $this->getDoctrine()
            ->getRepository('AppBundle:City')
            ->findBy(['countryCode' => $countryCode]);

        $data = [];
        if ($list) {
            foreach ($list as $city) {
                $data[$city->getId()] = $city->getName();
            }
        }

        $response = new JsonResponse();
        $response->setData(array($data));

        return $response;
    }

}
