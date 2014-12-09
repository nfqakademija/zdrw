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
        $sum1 = 0;
        for ($i = 1; $i <= 70; $i++) {
            $sum1++;
            $offer = new Offer();
            $offer->setTitle('With status 5. Number - '.$sum1);
            $offer->setDescription('Approved dare');
            $offer->setLongDesc('My very very description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
            $offer->setCategoryId(1);
            $offer->setStatus(5);
            $offer->setFinishDate(new \DateTime('tomorrow'));
            $offer->setViews(0);
            $user = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'TestUser1'));
            $offer->setOwner($user);
            $offer->setVideo("test.mp4");
            $manager->persist($offer);
        }

        // Second offer
        $sum2 = 0;
        for ($i = 1; $i <= 20; $i++) {
            $sum2++;
            $offer2 = new Offer();
            $offer2->setTitle('With status 3. Number - '.$sum2);
            $offer2->setDescription('Video uploaded, waiting for approval');
            $offer2->setLongDesc('My very very long description. Lorem ipsum dolor sit amet, consectetur adipiscing.');
            $offer2->setCategoryId(1);
            $offer2->setStatus(3);
            $offer2->setParticipant($user);
            $offer2->setFinishDate(new \DateTime('tomorrow'));
            $offer2->setViews(0);
            $user2 = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));
            $offer2->setOwner($user2);
            $manager->persist($offer2);
        }

        // Third offer
        $sum3 = 0;
        for ($i = 1; $i <= 50; $i++) {
            $sum3++;
            $offer3 = new Offer();
            $offer3->setTitle('With status 1. Number - '.$sum3);
            $offer3->setDescription('My third description. Nunc commodo mollis velit, ornare ultrices enim facili.');
            $offer3->setLongDesc('My very very long description. Lorem ipsum dolor sit amet, consectetur adipiscin.');
            $offer3->setCategoryId(1);
            $offer3->setStatus(1);
            $offer3->setFinishDate(new \DateTime('tomorrow'));
            $offer3->setViews(0);
            $user3 = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'TestUser1'));
            $offer3->setOwner($user3);
            $manager->persist($offer3);
        }

        // Fourth offer
        $sum4 = 0;
        for ($i = 1; $i <= 20; $i++) {
            $sum4++;
            $offer4 = new Offer();
            $offer4->setTitle('With status 4. Number - '.$sum4);
            $offer4->setDescription('Declined, waiting for admin approval');
            $offer4->setLongDesc('My very very long description. Lorem ipsum dolor sit amet, consectetur adipiscin.');
            $offer4->setCategoryId(1);
            $offer4->setStatus(4);
            $offer4->setFinishDate(new \DateTime('tomorrow'));
            $offer4->setViews(0);
            $user4 = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'TestUser1'));
            $offer4->setOwner($user4);
            $manager->persist($offer4);
        }

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