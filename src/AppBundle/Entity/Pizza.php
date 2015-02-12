<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pizza
 *
 * @ORM\Table(name="pizza")
 * @ORM\Entity
 */
class Pizza
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="meat", type="boolean", nullable=false)
     */
    private $meat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fish", type="boolean", nullable=false)
     */
    private $fish;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vegetarian", type="boolean", nullable=false)
     */
    private $vegetarian;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hot", type="boolean", nullable=false)
     */
    private $hot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated", type="datetime", nullable=false)
     */
    private $lastUpdated;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set name
     *
     * @param string $name
     * @return Pizza
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
     * Set meat
     *
     * @param boolean $meat
     * @return Pizza
     */
    public function setMeat($meat)
    {
        $this->meat = $meat;

        return $this;
    }

    /**
     * Get meat
     *
     * @return boolean 
     */
    public function getMeat()
    {
        return $this->meat;
    }

    /**
     * Set fish
     *
     * @param boolean $fish
     * @return Pizza
     */
    public function setFish($fish)
    {
        $this->fish = $fish;

        return $this;
    }

    /**
     * Get fish
     *
     * @return boolean 
     */
    public function getFish()
    {
        return $this->fish;
    }

    /**
     * Set vegetarian
     *
     * @param boolean $vegetarian
     * @return Pizza
     */
    public function setVegetarian($vegetarian)
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    /**
     * Get vegetarian
     *
     * @return boolean 
     */
    public function getVegetarian()
    {
        return $this->vegetarian;
    }

    /**
     * Set hot
     *
     * @param boolean $hot
     * @return Pizza
     */
    public function setHot($hot)
    {
        $this->hot = $hot;

        return $this;
    }

    /**
     * Get hot
     *
     * @return boolean 
     */
    public function getHot()
    {
        return $this->hot;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return Pizza
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
