<?php

namespace AppBundle\Service;

use AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;


/**
 * Restaurant service
 *
 * @package AppBundle\Service
 */
class Restaurant
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
     * @param int $restaurantId
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\Restaurant
     */
    public function get($restaurantId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\Restaurant')->find($restaurantId);
        if (!$result) {
            throw new \InvalidArgumentException('Restaurant was not found: ' . $restaurantId);
        }

        return $result;
    }

    /**
     * @param Entity\Restaurant $restaurant
     * @param array             $opt
     *
     * @return array
     */
    public function getPizzas($restaurant, $opt)
    {
        $result = [];
        $pizzas = $this->filterPizzas($restaurant->getPizzas(), $opt['meat'], $opt['fish'], $opt['vegetarian'], $opt['hot']);

        foreach ($pizzas as $pizza) {
            $result[] = $pizza->getId();
        }

        return $result;
    }

    /**
     * @param int $cityId
     *
     * @return int
     */
    public function getRandomId($cityId)
    {
        $query = "SELECT restaurant_id
                  FROM `city_restaurant`
                  WHERE city_id = :cityId
                  ORDER BY RAND()
                  LIMIT 1";

        $statement = $this->em->getConnection()->prepare($query);
        $statement->bindValue(':cityId', $cityId, \PDO::PARAM_INT);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    /**
     *
     * @param Collection $pizzas
     * @param bool       $meat
     * @param bool       $fish
     * @param bool       $vegetarian
     * @param bool       $hot
     *
     * @return array
     */
    private function filterPizzas($pizzas, $meat, $fish, $vegetarian, $hot)
    {
        $result = $pizzas->filter(
            function($pizza) use ($meat, $fish, $vegetarian, $hot)  {
                return (
                    ($meat && $pizza->getMeat()) ||
                    ($fish && $pizza->getFish()) ||
                    ($vegetarian && $pizza->getVegetarian()) ||
                    ($hot && $pizza->getHot())
                );
            }
        );

        return $result;
    }

}