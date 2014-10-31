<?php

namespace Zdrw\OffersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zdrw\OffersBundle\ZdrwOffersBundle;
use Zdrw\UserBundle\ZdrwUserBundle;

class UserLoginTest extends WebTestCase
{
    private $em;

    public function setUp() {

        $client = self::createClient();
        $container = $client->getKernel()->getContainer();
        $em = $container->get('doctrine')->getManager();

        // Purge tables
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $executor->purge();

        // Load fixtures
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $fixtures = new \Zdrw\UserBundle\DataFixtures\ORM\LoadUserData;
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);
        $executor->execute($loader->getFixtures());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("admin")')->count());

    }
}
