<?php

namespace Zdrw\OffersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class setup extends WebTestCase
{
    protected $client = null;

    public function customLogin($name, $pass)
    {
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Login')->link();
        $crawler = $this->client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => $name,
            '_password' => $pass
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        return $crawler;
    }

    public function logIn()
    {
        $crawler = $this->customLogin('admin', 'test');
        return $crawler;
    }

    public function logIn2()
    {
        $crawler = $this->customLogin('TestUser1', 'user');
        return $crawler;
    }
}
