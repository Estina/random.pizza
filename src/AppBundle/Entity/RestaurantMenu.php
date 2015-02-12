<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RestaurantMenu
 *
 * @ORM\Table(name="restaurant_menu")
 * @ORM\Entity
 */
class RestaurantMenu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="restaurant_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $restaurantId;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $menuId;



    /**
     * Set restaurantId
     *
     * @param integer $restaurantId
     * @return RestaurantMenu
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

    /**
     * Set menuId
     *
     * @param integer $menuId
     * @return RestaurantMenu
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer 
     */
    public function getMenuId()
    {
        return $this->menuId;
    }
}
