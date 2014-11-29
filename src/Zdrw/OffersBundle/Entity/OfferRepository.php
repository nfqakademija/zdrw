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
                'SELECT p FROM ZdrwOffersBundle:Offer p WHERE (p.title LIKE :keyword OR p.description LIKE :keyword)
                AND p.status IN(1,2)'
            )->setParameter('keyword', '%'.$keyword.'%')
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
                'SELECT p FROM ZdrwOffersBundle:Offer p WHERE (p.title LIKE :keyword OR p.description LIKE :keyword)
                AND p.status = 5'
            )->setParameter('keyword', '%'.$keyword.'%')
            ->getResult();
        return $dares;
    }
}
