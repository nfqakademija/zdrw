<?php

namespace Zdrw\OffersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\OffersBundle\Entity\Offer;

class LoadRewardsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // First offer
        $offer = new Offer();

        $user = $manager->getRepository('ZdrwUserBundle:User')->findOneByUsername('admin');
        $offer->setOwner($user);
        $offer->setPoints(500);

        $manager->persist($offer);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}