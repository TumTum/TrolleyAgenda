<?php

namespace Trolley\AgendaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->markTestSkipped();
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
