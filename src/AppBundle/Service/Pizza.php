<?php

namespace AppBundle\Service;

use AppBundle\Entity;
use Doctrine\ORM\EntityManager;

/**
 * Pizza service
 *
 * @package AppBundle\Service
 */
class Pizza
{
    /** @var EntityManager */
    private $em;

    /** @var Slug */
    private $slugService;

    /**
     * @param EntityManager $em
     * @param Slug          $slugService
     */
    public function __construct(EntityManager $em, Slug $slugService)
    {
        $this->em = $em;
        $this->slugService = $slugService;
    }

    /**
     * @param array $pizzas
     * @param int   $qty
     *
     * @return array
     */
    public function getRandomIds($pizzas, $qty)
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
     * @param Entity\City       $city
     * @param Entity\Restaurant $restaurant
     * @param array             $options
     *
     * @return Entity\Result
     */
    public function save(Entity\City $city, Entity\Restaurant $restaurant, array $options)
    {
        $result = new Entity\Result();
        $result
            ->setOptions($options)
            ->setCountryCode($city->getCountryCode())
            ->setCityId($city->getId())
            ->setRestaurantId($restaurant->getId())
            ->setDateCreated(time());

        do {
            $slug = $this->slugService->generate();
        } while ($this->slugService->exists($slug));

        $result->setSlug($slug);
        $this->em->persist($result);
        $this->em->flush();

        return $result;
    }

    /**
     * @param int $resultId Result Id
     * @param int $pizzaId  Pizza Id
     * @param int $qty      Qty
     */
    public function addPizza($resultId, $pizzaId, $qty)
    {
        $this->em->getConnection()->insert('result_pizza', array(
            'result_id' => $resultId,
            'pizza_id' => $pizzaId,
            'qty' => $qty
        ));
    }




}