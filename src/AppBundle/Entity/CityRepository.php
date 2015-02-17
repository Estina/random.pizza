<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    /**
     * @param string $countryCode
     *
     * @return array
     */
    public function findAllByCountryCode($countryCode)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT c.id, c.name FROM AppBundle\Entity\City c WHERE c.countryCode = :countryCode ORDER BY c.name')
                    ->setParameter('countryCode', $countryCode);

        return $query->getResult();
    }


    public function findAllRestaurants($cityId)
    {
        $em = $this->getEntityManager();

    }
}