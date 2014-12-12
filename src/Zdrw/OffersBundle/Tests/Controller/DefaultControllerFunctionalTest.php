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

    private function customAssert($setup, $route, $assert)
    {
        $crawler = $setup->client->request('GET', $route);
        $this->assertGreaterThan(0, $crawler->filter($assert)->count());
    }

    private function customDataTests($setup, $route, $selectLink, $button, $formData, $result)
    {
        $setup->client->followRedirects(true);
        $crawler = $setup->client->request('GET', $route);
        $link = $crawler->selectLink($selectLink)->link();
        $crawler = $setup->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton($button);
        $form = $buttonCrawlerNode->form($formData);
        $crawler = $setup->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter($result)->count());
    }

    public function testLogin()
    {
        $setup = $this->loginForTest();
        $route = '/';
        $assert = 'html:contains("admin")';
        $this->assertTrue($setup->client->getResponse()->isSuccessful());
        $this->customAssert($setup, $route, $assert);
    }


    public function testDares()
    {
        $setup = $this->loginForTest();
        $route = '/dares';
        $assert = '.dare-article h4 a:contains("With status 1")';
        $this->customAssert($setup, $route, $assert);
    }

    public function testSearch()
    {
        $setup = $this->loginForTest();
        $route = '/';
        $selectLink= null;
        $button = "search";
        $formData = array(
            'keyword' => 'status'
        );
        $result = '.dare-article h4 a:contains("With status 1")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
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
        $route = '/dares';
        $selectLink= 'With status 1. Number - 49';
        $button = "Add";
        $formData = array('points' => 1);
        $result = '.alert-success:contains("You have successfully added points to this challenge")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
    }

    public function testAddPoints2()
    {
        $setup = $this->loginForTest();
        $route = '/dares';
        $selectLink= 'With status 1. Number - 49';
        $button = "Add";
        $formData = array('points' => 1000000);
        $result = '.alert-danger:contains("You don\'t have enough points")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
    }

    public function testAcceptVideo()
    {
        $setup = $this->loginForTest();
        $route = '/admin';
        $selectLink= 'With status 4. Number - 1';
        $button = "Accept video";
        $formData = null;
        $result = 'html:contains("Challenge fulfilment proof")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
    }

    public function testDeclineVideo()
    {
        $setup = $this->loginForTest();
        $route = '/admin';
        $selectLink= 'With status 4. Number - 2';
        $button = "Decline video";
        $formData = null;
        $result = 'html:contains("Add reward")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
    }

    public function testOwnerAcceptVideo()
    {
        $setup = $this->loginForTest2();
        $route = '/profile';
        $selectLink= 'With status 3. Number - 1';
        $button = "Accept video";
        $formData = null;
        $result = 'html:contains("Challenge fulfilment proof")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
    }

    public function testOwnerDeclineVideo()
    {
        $setup = $this->loginForTest2();
        $route = '/profile';
        $selectLink= 'With status 3. Number - 2';
        $button = "Decline video";
        $formData = null;
        $result = '.alert-warning:contains("Someone has uploaded video for this dare. Owner rejected video, waiting for admin review")';
        $this->customDataTests($setup, $route, $selectLink, $button, $formData, $result);
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
        $route = '/stares';
        $assert = '.stare-article h4 a:contains("With status 5")';
        $this->customAssert($setup, $route, $assert);
    }

    public function testProfile1()
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
        $route = '/user/TestUser1';
        $assert = '.profile p:contains("TestUser1")';
        $this->customAssert($setup, $route, $assert);
    }
    public function testAdminIndex()
    {
        $setup = $this->loginForTest();
        $route = '/admin';
        $assert = 'h4 a:contains("With status 4")';
        $this->customAssert($setup, $route, $assert);
    }
}