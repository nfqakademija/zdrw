<?php

namespace Zdrw\OffersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserLoginTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Login')->link();
        $crawler = $client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('_submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'test'

        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("admin")')->count());

    }
}
