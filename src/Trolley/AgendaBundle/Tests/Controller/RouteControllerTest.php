<?php

namespace Trolley\AgendaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RouteControllerTest
 *
 * @package Trolley\AgendaBundle\Tests\Controller
 *
 * @expectedException
 */
class RouteControllerTest extends WebTestCase
{
    public function testRoute()
    {
        $this->markTestSkipped();

        $client = static::createClient();
        $crawler = $client->request('GET', '/route');
    }

}
