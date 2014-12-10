<?php

namespace Zdrw\OffersBundle\Tests\Controller;

use Zdrw\OffersBundle\Tests\Controller\setup;

class DefaultControllerFunctionalTest extends setup
{
    private function loginForTest()
    {
        $setup = new setup();
        $setup->logIn();
        return $setup;
    }
    private function loginForTest2()
    {
        $setup = new setup();
        $setup->logIn2();
        return $setup;
    }

    public function testLogin()
    {
        $setup = new setup();
        $setup->logIn();
        $crawler = $setup->client->request('GET', '/');
        $this->assertTrue($setup->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("admin")')->count());
    }


    public function testDares()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/dares');
        $this->assertGreaterThan(0, $crawler->filter('.dare-article h4 a:contains("With status 1")')->count());
    }

    public function testSearch()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/');
        $button = $crawler->selectButton('search');
        $form = $button->form(array(
            'keyword' => 'status'
        ));
        $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('.dare-article h4 a:contains("With status 1")')->count());
    }

    public function testReservation()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/dares');
        $link = $crawler->selectLink('With status 1. Number - 50')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("I'll do it");
        $form = $buttonCrawlerNode->form();
        $crawler = $setup->client->submit($form);
        $this->assertCount(0, $crawler->filter('html:contains("I\'ll do it")'));
    }

    public function testAddPoints1()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/dares');
        $link = $crawler->selectLink('With status 1. Number - 49')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Add");
        $form = $buttonCrawlerNode->form(array('points' => 1));
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('.alert-success:contains("You have successfully added points to this challenge")')->count());
    }

    public function testAddPoints2()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/dares');
        $link = $crawler->selectLink('With status 1. Number - 49')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Add");
        $form = $buttonCrawlerNode->form(array('points' => 1000000));
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('.alert-danger:contains("You don\'t have enough points")')->count());
    }

    public function testAcceptVideo()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/admin');
        $link = $crawler->selectLink('With status 4. Number - 1')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Accept video");
        $form = $buttonCrawlerNode->form();
        $crawler = $setup->client->submit($form);
        $this->assertCount(1, $crawler->filter('html:contains("Challenge fulfilment proof")'));
    }

    public function testDeclineVideo()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/admin');
        $link = $crawler->selectLink('With status 4. Number - 2')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Decline video");
        $form = $buttonCrawlerNode->form();
        $crawler = $setup->client->submit($form);
        $this->assertCount(1, $crawler->filter('html:contains("Add reward")'));
    }

    public function testOwnerAcceptVideo()
    {
        $setup = $this->loginForTest2();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/profile');
        $link = $crawler->selectLink('With status 3. Number - 1')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Accept video");
        $form = $buttonCrawlerNode->form();
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Challenge fulfilment proof")')->count());
    }

    public function testOwnerDeclineVideo()
    {
        $setup = $this->loginForTest2();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/profile');
        $link = $crawler->selectLink('With status 3. Number - 2')->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton("Decline video");
        $form = $buttonCrawlerNode->form();
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('.alert-warning:contains("Dare is completed. Waiting for admin review")')->count());
    }

    public function testNewDare1()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/new');

        $buttonCrawlerNode = $crawler->selectButton("offer[save]");
        $form = $buttonCrawlerNode->form((array(
            'offer[title]' => 'New dare',
            'offer[description]' => 'Short description',
            'offer[longDesc]' => 'Long description',
            'offer[rewards]' => 20
        )));
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('a:contains("New dare")')->count());
    }

    public function testNewDare2()
    {
        $setup = $this->loginForTest();
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', '/new');

        $buttonCrawlerNode = $crawler->selectButton("offer[save]");
        $form = $buttonCrawlerNode->form((array(
            'offer[title]' => 'New dare',
            'offer[description]' => 'Short description',
            'offer[longDesc]' => 'Long description',
            'offer[rewards]' => 2000000000000
        )));
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('.alert-danger p:contains("You don\'t have enough points")')->count());
    }

    public function testDare()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/dares');
        $link = $crawler->selectLink('With status 1. Number - 47')->link();
        $crawler = $setup->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('.i-did-it:contains("I did it")')->count());
    }

    public function testStares()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/stares');
        $this->assertGreaterThan(0, $crawler->filter('.stare-article h4 a:contains("With status 5")')->count());
    }

    public function testProfile()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/profile/');
        $this->assertGreaterThan(0, $crawler->filter('.tab-content:contains("Test notification 1")')->count());
        $link = $crawler->selectLink('Posted dares')->link();
        $crawler = $setup->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('.tab-content h4:contains("With status 3")')->count());
    }
    public function testUser()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/user/TestUser1');
        $this->assertGreaterThan(0, $crawler->filter('.profile p:contains("TestUser1")')->count());
    }
    public function testAdminIndex()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/admin');
        $this->assertGreaterThan(0, $crawler->filter('h4 a:contains("With status 4")')->count());
    }
}
