<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuPizza
 *
 * @ORM\Table(name="menu_pizza")
 * @ORM\Entity
 */
class MenuPizza
{
    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $menuId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pizza_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $pizzaId;



    /**
     * Set menuId
     *
     * @param integer $menuId
     * @return MenuPizza
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

    /**
     * Set pizzaId
     *
     * @param integer $pizzaId
     * @return MenuPizza
     */
    public function setPizzaId($pizzaId)
    {
        $this->pizzaId = $pizzaId;

        return $this;
    }

    /**
     * Get pizzaId
     *
     * @return integer 
     */
    public function getPizzaId()
    {
        return $this->pizzaId;
    }
}
