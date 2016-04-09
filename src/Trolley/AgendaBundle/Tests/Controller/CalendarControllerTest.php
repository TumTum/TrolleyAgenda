<?php

namespace Trolley\AgendaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntityTrait;
use Trolley\AgendaBundle\Tests\phpunit_utils\createUserDayRelationshipsTrait;
use Trolley\AgendaBundle\Tests\phpunit_utils\webClientUserLoginTrait;

class CalendarControllerTest extends WebTestCase
{
    use autoClearEntityTrait;
    use createUserDayRelationshipsTrait;
    use webClientUserLoginTrait;

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

    public function testSignoffMeFromADay()
    {
        /**
         * @var User $user
         * @var Day $day
         * @var Router $router
         */
        $client = static::createClient();
        $user = $this->login($client);
        $user = self::getDoctrine()->getRepository('TrolleyAgendaBundle:User')->find($user->getId());

        $day = $this->createOneDay();
        $day->addUser($user);

        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_signoffuserfromday', ['day'  => $day->getId()]);

        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: ('.$client->getResponse()->getStatusCode().') trolley_agenda_calendar_addusertoday');

        $dayDB = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $userLinked = $dayDB->getTaUsers();
        $this->assertCount(0, $userLinked);
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