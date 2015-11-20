<?php

namespace AppBundle\Entity;

use AppBundle\Entity\City;
use AppBundle\Entity\Pizza;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Restaurant
 *
 * @ORM\Table(name="restaurant")
 * @ORM\Entity
 */
class Restaurant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated", type="datetime", nullable=false)
     */
    private $lastUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=false)
     */
    private $approved;

    /**
     * @ORM\ManyToMany(targetEntity="City")
     * @ORM\JoinTable(name="city_restaurant",
     *      joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")}
     *      )
     */
    private $cities;

    /**
     * @ORM\ManyToMany(targetEntity="Pizza")
     * @ORM\JoinTable(name="restaurant_pizza",
     *      joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pizza_id", referencedColumnName="id")}
     *      )
     */
    private $pizzas;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->cities = new ArrayCollection();
        $this->pizzas = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Restaurant
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
     * Set created
     *
     * @param \DateTime $created
     * @return Restaurant
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return Restaurant
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime 
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set URL
     *
     * @param string $url
     * @return Restaurant
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Restaurant
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Adds city
     *
     * @param City $city
     *
     * @return City
     */
    public function addCity(City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Removes city
     *
     * @param City $city
     */
    public function removeCity(City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Adds pizza
     *
     * @param Pizza $pizza
     *
     * @return Pizza
     */
    public function addPizza(Pizza $pizza)
    {
        $this->pizzas[] = $pizza;

        return $this;
    }

    /**
     * Removes pizza
     *
     * @param Pizza $pizza
     */
    public function removePizza(Pizza $pizza)
    {
        $this->pizzas->removeElement($pizza);
    }

    /**
     * Get pizzas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPizzas()
    {
        return $this->pizzas;
    }
}
