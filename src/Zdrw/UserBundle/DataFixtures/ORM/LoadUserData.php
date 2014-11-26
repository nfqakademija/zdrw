<?php

namespace Zdrw\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zdrw\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setEmail('test@gmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_ADMIN');


        $user = new User();
        $user->setUsername('TestUser1');
        $user->setPlainPassword('user');
        $user->setEmail('testUser1@gmail.com');
        $user->setEnabled(true);

        $manager->persist($userAdmin);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}