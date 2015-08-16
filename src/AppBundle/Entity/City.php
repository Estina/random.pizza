<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Restaurant;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city", indexes={@ORM\Index(name="country_code", columns={"country_code"})})
 * @ORM\Entity
 */
class City
{
    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Restaurant")
     * @ORM\JoinTable(name="city_restaurant",
     *      joinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")}
     *      )
     */
    private $restaurants;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }


    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return City
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getRestaurants()
    {
        return $this->restaurants;
    }

    /**
     * @return ArrayCollection
     */
    public function getApprovedRestaurants()
    {
        $result = [];
        foreach ($this->restaurants as $restaurant) {
            if ($restaurant->getApproved()) {
                $result[] = $restaurant;
            }
        }

        return $result;
    }

    /**
     * Adds restaurant
     *
     * @param Restaurant $restaurant
     * @return City
     */
    public function addRestaurant(\AppBundle\Entity\Restaurant $restaurant)
    {
        $this->restaurants[] = $restaurant;

        return $this;
    }

    /**
     * Removes restaurant
     *
     * @param Restaurant $restaurant
     */
    public function removeRestaurant(Restaurant $restaurant)
    {
        $this->restaurants->removeElement($restaurant);
    }
}
