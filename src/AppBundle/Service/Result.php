<?php

namespace AppBundle\Service;

use AppBundle\Entity;
use Doctrine\ORM\EntityManager;

/**
 * Result service
 *
 * @package AppBundle\Service
 */
class Result
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
     * @param string            $ip
     *
     * @return Entity\Result
     */
    public function save(Entity\City $city, Entity\Restaurant $restaurant, array $options, $ip)
    {
        $result = new Entity\Result();
        $result
            ->setOptions($options)
            ->setCountryCode($city->getCountryCode())
            ->setCityId($city->getId())
            ->setRestaurantId($restaurant->getId())
            ->setDateCreated(time())
            ->setIp($ip);

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

    /**
     * @param string $slug
     *
     * @return array
     */
    public function getResult($slug)
    {
        $query = "SELECT result.id AS result_id,
                         result.date_created,
                         result.options,
                         result.slug,
                         city.name AS city,
                         restaurant.name AS restaurant_name,
                         restaurant.url
                  FROM `result`
                  INNER JOIN city
                    ON city.id = result.city_id
                  INNER JOIN restaurant
                    ON restaurant.id = result.restaurant_id
                  WHERE result.slug = :slug";

        $statement = $this->em->getConnection()->prepare($query);
        $statement->bindValue(':slug', $slug);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $resultId
     *
     * @return array
     */
    public function getPizzas($resultId)
    {
        $query = "SELECT rp.qty,
                         p.name
                  FROM `result_pizza` rp
                  INNER JOIN pizza p
                    ON p.id = rp.pizza_id
                  WHERE rp.result_id = :resultId";

        $statement = $this->em->getConnection()->prepare($query);
        $statement->bindValue(':resultId', $resultId);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param Entity\Pizza $pizza
     *
     * @return Entity\Pizza
     */
    public function savePizza(Entity\Pizza $pizza)
    {
        $this->em->persist($pizza);
        $this->em->flush();

        return $pizza;
    }

    /**
     * @param int $limit
     *
     * @return \Generator
     */
    public function getResults($limit)
    {
        $query = "SELECT slug, date_created
                  FROM `result`
                  ORDER BY id DESC";

        $statement = $this->em->getConnection()->prepare($query);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            yield $row;
            if (0 == --$limit) {
                break;
            }
        }
    }
}