<?php

namespace Zdrw\OffersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class setup extends WebTestCase
{
    protected $client = null;

    public function logIn()
    {
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Login')->link();
        $crawler = $this->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'test'
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        return $crawler;
    }

    public function logIn2()
    {
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Login')->link();
        $crawler = $this->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'TestUser1',
            '_password' => 'user'
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        return $crawler;
    }
}
