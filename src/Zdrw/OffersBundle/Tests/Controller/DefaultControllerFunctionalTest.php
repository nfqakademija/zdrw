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

    public function testDare()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/dares');
        $link = $crawler->selectLink('With status 1')->link();
        $crawler = $setup->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('.i-did-it:contains("I did it")')->count());
    }

    public function testStares()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/stares');
        $this->assertGreaterThan(0, $crawler->filter('.dare-article h4 a:contains("With status 5")')->count());
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
        $crawler = $setup->client->request('GET', '/user/testUser1');
        $this->assertGreaterThan(0, $crawler->filter('.profile p:contains("testUser1@gmail.com")')->count());
    }
    public function testAdminIndex()
    {
        $setup = $this->loginForTest();
        $crawler = $setup->client->request('GET', '/admin');
        $this->assertGreaterThan(0, $crawler->filter('h4 a:contains("With status 4")')->count());
    }
}
