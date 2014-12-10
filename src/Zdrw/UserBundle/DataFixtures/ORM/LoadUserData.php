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
        $userAdmin->setNickname('admin');
        $userAdmin->setAvatar('default.jpg');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setEmail('test@gmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_ADMIN');
        $userAdmin->setPoints(500);


        $user = new User();
        $user->setUsername('TestUser1');
        $user->setNickname('TestUser1');
        $user->setAvatar('default.jpg');
        $user->setPlainPassword('user');
        $user->setEmail('testUser1@gmail.com');
        $user->setEnabled(true);
        $user->setPoints(100);

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