<?php

namespace Zdrw\OffersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\OffersBundle\Entity\Offer;

class LoadOffersData extends AbstractFixture implements OrderedFixtureInterface
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
        $offer->setLongDesc('My very very description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $offer->setCategoryId(1);
        $offer->setStatus(3);
        $offer->setFinishDate(new \DateTime('tomorrow'));
        $offer->setViews(0);
        $user = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));
        $offer->setOwner($user);
        $offer->setVideo("test.mp4");
        $offer->setParticipantId(1);

        // Second offer
        $offer2 = new Offer();
        $offer2->setTitle('My Title 2');
        $offer2->setDescription('My second description. Nunc non tortor a nunc interdum molestie nec ac nisl.');
        $offer2->setLongDesc('My very very long description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $offer2->setCategoryId(1);
        $offer2->setStatus(1);
        $offer2->setFinishDate(new \DateTime('tomorrow'));
        $offer2->setViews(0);
        $user2 = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));;
        $offer2->setOwner($user2);
        $offer2->setParticipantId(1);

        // Third offer
        $offer3 = new Offer();
        $offer3->setTitle('My Title 3');
        $offer3->setDescription('My third description. Nunc commodo mollis velit, ornare ultrices enim facilisis et.');
        $offer3->setLongDesc('My very very long description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $offer3->setCategoryId(1);
        $offer3->setStatus(1);
        $offer3->setFinishDate(new \DateTime('tomorrow'));
        $offer3->setViews(0);
        $user3 = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));
        $offer3->setOwner($user3);
        $offer3->setParticipantId(1);

        $manager->persist($offer);
        $manager->persist($offer2);
        $manager->persist($offer3);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}