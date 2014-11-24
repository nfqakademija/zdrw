<?php

namespace Zdrw\OffersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\OffersBundle\Entity\Reward;

class LoadRewardsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // First offer
        $reward = new Reward();

        $user = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));
        $reward->setUser($user);
        $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('title' => 'My Title 1'));
        $reward->setOffer($offer);
        $reward->setPoints(500);

        $manager->persist($reward);
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