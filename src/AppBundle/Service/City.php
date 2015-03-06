<?php

namespace AppBundle\Service;

use AppBundle\Entity;
use Doctrine\ORM\EntityManager;

/**
 * City service
 *
 * @package AppBundle\Service
 */
class City
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
     * @param string $countryCode
     *
     * @return array
     */
    public function getCitiesByCountryCode($countryCode)
    {
        $repository = $this->em->getRepository('AppBundle\Entity\City');

        return $repository->findBy(['countryCode' => $countryCode]);
    }

    /**
     * @param int $cityId
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\City
     */
    public function get($cityId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\City')->find($cityId);
        if (!$result) {
            throw new \InvalidArgumentException('City was not found: ' . $cityId);
        }

        return $result;
    }


}