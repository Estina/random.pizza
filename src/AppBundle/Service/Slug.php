<?php

namespace AppBundle\Service;

use AppBundle\Entity\City;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Result;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slug service
 *
 * @package AppBundle\Service
 */
class Slug
{
    const LENGTH = 6;

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
     * @return string
     */
    public function generate()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $result = '';
        for ($i = 0; $i < self::LENGTH; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }

        return $result;
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function exists($slug)
    {
        $query = "SELECT `id` FROM `result` WHERE `slug` = :slug";
        $statement = $this->em->getConnection()->prepare($query);
        $statement->bindValue(':slug', $slug);

        return (bool) $statement->fetchColumn();
    }


}