<?php

namespace Trolley\AgendaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntity;
use Trolley\AgendaBundle\Tests\phpunit_utils\createUserDayRelationships;
use Trolley\AgendaBundle\Tests\phpunit_utils\webClientUserLoginTraid;

class CalendarControllerTest extends WebTestCase
{
    use autoClearEntity;
    use createUserDayRelationships;
    use webClientUserLoginTraid;

    public function testAddMeToAday()
    {
        /**
         * @var User $user
         * @var Day $day
         * @var Router $router
         */
        $client = static::createClient();
        $user = $this->loginAsAdmin($client);
        $day = $this->createOneDay();

        $this->saveInDb([$day]);

        $url = $this->_getUrl(
            'trolley_agenda_calendar_addusertoday',
            [
                'day'  => $day->getId(),
                'user' => $user->getId()
            ]);

        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: ('.$client->getResponse()->getStatusCode().') trolley_agenda_calendar_addusertoday');

        $dayDB = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $userLinked = $dayDB->getTaUsers();
        $this->assertCount(1, $userLinked);
    }

    /**
     * @param string $routename
     */
    protected function _getUrl($routename, $param = null)
    {
        $kernel = self::$kernel->getContainer();
        $router = $kernel->get('router');
        return $router->generate($routename, $param);
    }

}