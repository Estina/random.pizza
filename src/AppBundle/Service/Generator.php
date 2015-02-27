<?php

namespace AppBundle\Service;

use AppBundle\Entity\City;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Result;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Generator service
 *
 * @package AppBundle\Service
 */
class Generator
{
    const HASH_LENGTH = 6;

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
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function generatePizzas(Request $request)
    {
        $options = $this->getOptions($request);
        $city = $this->getCity($options['cityId']);
        $restaurant = $this->getRestaurant($options['restaurantId']);
        $pizzas = $restaurant->getPizzasFiltered(
            $options['meat'],
            $options['fish'],
            $options['vegetarian'],
            $options['hot']
        );

        if (!$pizzas) {
            throw new \InvalidArgumentException(
                sprintf('No pizzas found, options: ', print_r($options, true))
            );
        }

        $ids = $this->getRandomIds($pizzas, $options['qty']);
        $ids = array_count_values($ids);
        $result = $this->saveResult($options, $city, $restaurant);
        if ($result) {
            foreach ($ids as $pizzaId => $qty) {
                $this->saveGeneratedPizza($result->getId(), $pizzaId, $qty);
            }
        }

        return $result->getHash();
    }

    /**
     * @param array      $options
     * @param City       $city
     * @param Restaurant $restaurant
     *
     * @return Result
     */
    private function saveResult(array $options, City $city, Restaurant $restaurant)
    {
        $result = new Result();
        $result
            ->setOptions($options)
            ->setCountryCode($city->getCountryCode())
            ->setCityId($city->getId())
            ->setRestaurantId($restaurant->getId())
            ->setDateCreated(time());

        do {
            $hash = $this->generateHash();
        } while($this->hashExists($hash));

        $result->setHash($hash);
        $this->em->persist($result);
        $this->em->flush();

        return $result;
    }

    /**
     * @param int $resultId Result Id
     * @param int $pizzaId  Pizza Id
     * @param int $qty      Qty
     */
    private function saveGeneratedPizza($resultId, $pizzaId, $qty)
    {
        $this->em->getConnection()->insert('result_pizza', array(
            'result_id' => $resultId,
            'pizza_id' => $pizzaId,
            'qty' => $qty
        ));
    }

    /**
     * @return string
     */
    private function generateHash()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $hash = '';
        for ($i = 0; $i < self::HASH_LENGTH; $i++) {
            $hash .= $characters[rand(0, $charactersLength - 1)];
        }

        return $hash;
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    private function hashExists($hash)
    {
        $query = "SELECT `id` FROM `result` WHERE `hash` = :hash";
        $statement = $this->em->getConnection()->prepare($query);
        $statement->bindValue(':hash', $hash);

        return (bool) $statement->fetchColumn();
    }

    /**
     * @param int $cityId
     *
     * @throws \InvalidArgumentException
     *
     * @return City
     */
    private function getCity($cityId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\City')->find($cityId);
        if (!$result) {
            throw new \InvalidArgumentException('City was not found: ' . $cityId);
        }

        return $result;
    }

    /**
     * @param int $restaurantId
     *
     * @throws \InvalidArgumentException
     *
     * @return Restaurant
     */
    private function getRestaurant($restaurantId)
    {
        $result = $this->em->getRepository('AppBundle\Entity\Restaurant')->find($restaurantId);
        if (!$result) {
            throw new \InvalidArgumentException('Restaurant was not found: ' . $restaurantId);
        }

        return $result;
    }

    /**
     * @param array $pizzas
     * @param int   $qty
     *
     * @return array
     */
    private function getRandomIds($pizzas, $qty)
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
     * @param Request $request
     *
     * @return array
     */
    private function getOptions(Request $request)
    {
        $options = [
            'countryCode' => substr(preg_replace('/[^a-z]/', '', $request->get('countryCode')), 0, 2),
            'cityId' => (int) $request->get('cityId'),
            'restaurantId' => (int) $request->get('restaurantId'),
            'qty' => (int) $request->get('qty'),
            'meat' => (bool) $request->get('meat'),
            'fish' => (bool) $request->get('fish'),
            'vegetarian' => (bool) $request->get('vegetarian'),
            'hot' => (bool) $request->get('hot')
        ];

        $options['qty'] = max(1, $options['qty']);
        $options['qty'] = min(10, $options['qty']);

        return $options;
    }
}