<?php

namespace Zdrw\OffersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\OffersBundle\Entity\Notification;

class LoadNotificationsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $notification = new Notification();
        $notification->setNotification("Test notification 1");
        $user = $manager->getRepository('ZdrwUserBundle:User')->findOneBy(array('username' => 'admin'));
        $notification->setUser($user);
        $notification->setDate(new \DateTime("now"));
        $notification->setSeen(0);

        $notification1 = new Notification();
        $notification1->setNotification("Test notification 2");
        $notification1->setUser($user);
        $notification1->setDate(new \DateTime("now"));
        $notification1->setSeen(0);

        $manager->persist($notification);
        $manager->persist($notification1);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}