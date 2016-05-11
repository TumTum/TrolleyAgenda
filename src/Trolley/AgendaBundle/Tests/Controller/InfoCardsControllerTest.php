<?php

namespace Trolley\AgendaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InfoCardsControllerTest extends WebTestCase
{
    public function testAllusercards()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'cards');
    }

}
