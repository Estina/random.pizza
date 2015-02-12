<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CityRestaurant
 *
 * @ORM\Table(name="city_restaurant")
 * @ORM\Entity
 */
class CityRestaurant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="restaurant_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $restaurantId;



    /**
     * Set cityId
     *
     * @param integer $cityId
     * @return CityRestaurant
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer 
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set restaurantId
     *
     * @param integer $restaurantId
     * @return CityRestaurant
     */
    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    /**
     * Get restaurantId
     *
     * @return integer 
     */
    public function getRestaurantId()
    {
        return $this->restaurantId;
    }
}
