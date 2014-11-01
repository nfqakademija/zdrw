<?php

namespace Zdrw\OffersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\OffersBundle\Entity\Offer;

class OffersData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // First offer
        $offer = new Offer();
        $offer->setTitle('My Title 1');
        $offer->setDescription('My first description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $offer->setCategoryId(1);
        $offer->setStatus(1);
        $offer->setStartDate(new \DateTime('now'));
        $offer->setFinishDate(new \DateTime('tomorrow'));
        $offer->setViews(0);
        $offer->setOwnerId(1);
        $offer->setParticipantId(1);

        // Second offer
        $offer2 = new Offer();
        $offer2->setTitle('My Title 2');
        $offer2->setDescription('My second description. Nunc non tortor a nunc interdum molestie nec ac nisl.');
        $offer2->setCategoryId(1);
        $offer2->setStatus(1);
        $offer2->setStartDate(new \DateTime('now'));
        $offer2->setFinishDate(new \DateTime('tomorrow'));
        $offer2->setViews(0);
        $offer2->setOwnerId(1);
        $offer2->setParticipantId(1);

        // Third offer
        $offer3 = new Offer();
        $offer3->setTitle('My Title 3');
        $offer3->setDescription('My third description. Nunc commodo mollis velit, ornare ultrices enim facilisis et.');
        $offer3->setCategoryId(1);
        $offer3->setStatus(1);
        $offer3->setStartDate(new \DateTime('now'));
        $offer3->setFinishDate(new \DateTime('tomorrow'));
        $offer3->setViews(0);
        $offer3->setOwnerId(1);
        $offer3->setParticipantId(1);

        $manager->persist($offer);
        $manager->persist($offer2);
        $manager->persist($offer3);
        $manager->flush();
    }
}