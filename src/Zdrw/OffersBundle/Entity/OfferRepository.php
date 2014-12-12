<?php

namespace Zdrw\OffersBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OfferRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfferRepository extends EntityRepository
{
    /**
     * Method to search for dares
     *
     * @return Offer
     */
    public function searchForDares($keyword)
    {
        $dares = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ZdrwOffersBundle:Offer p WHERE (p.title LIKE :keyword OR p.description LIKE :keyword) AND p.status IN(1,2)'
            )->setParameter('keyword', '%'.$keyword.'%')
            ->setMaxResults(12)
            ->getResult();
        return $dares;
    }
    /**
     * Method to search for stares
     *
     * @return Offer
     */
    public function searchForStares($keyword)
    {
        $dares = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ZdrwOffersBundle:Offer p WHERE (p.title LIKE :keyword OR p.description LIKE :keyword) AND p.status = 5'
            )->setParameter('keyword', '%'.$keyword.'%')
            ->setMaxResults(16)
            ->getResult();
        return $dares;
    }

    /**
     * Method to count from query
     *
     * @param $where
     * @return mixed
     */
    private function customCount($where)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where($where)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * Method to count dares
     *
     * @return mixed
     */
    public function countDares()
    {
        $where = 'a.status = 1 OR a.status = 2';
        $this-> customCount($where);

        return $this-> customCount($where);
    }

    /**
     * Method to count dares
     *
     * @return mixed
     */
    public function countStares()
    {
        $where = 'a.status = 5';
        $this-> customCount($where);

        return $this-> customCount($where);
    }
}
