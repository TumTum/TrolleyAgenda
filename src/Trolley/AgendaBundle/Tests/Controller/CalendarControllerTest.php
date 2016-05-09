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
         * @var User   $user
         * @var Day    $day
         * @var Router $router
         */
        $client = static::createClient();
        $user = $this->login($client);
        $day = $this->createOneDay();

        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_addusertoday', ['day' => $day->getId()]);

        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_addusertoday');

        $dayDB = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $userLinked = $dayDB->getTaUsers();
        $this->assertCount(1, $userLinked);
    }

    public function testSignoffMeFromADay()
    {
        /**
         * @var User   $user
         * @var Day    $day
         * @var Router $router
         */
        $client = static::createClient();
        $user = $this->login($client);
        $user = self::getDoctrine()->getRepository('TrolleyAgendaBundle:User')->find($user->getId());

        $day = $this->createOneDay();
        $day->addUser($user);

        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_signoffuserfromday', ['day' => $day->getId()]);

        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_addusertoday');

        $dayDB = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $userLinked = $dayDB->getTaUsers();
        $this->assertCount(0, $userLinked);
    }

    /**
     * Der Admin Acceptiert den User
     */
    public function testAdminAcceptUser()
    {
        /**
         * @var User   $user
         * @var Day    $day
         * @var Day    $day2
         * @var Day    $dayDB
         */
        list($user, $day, $day2) = $this->createUserInTowDays();

        $this->saveInDb([$user, $day, $day2]);

        $url = $this->_getUrl('trolley_agenda_calendar_adminacceptuser', [
            'day' => $day->getId(),
            'user' => $user->getId(),
        ]);

        $client = static::createClient();
        $this->loginAsAdmin($client);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_adminacceptuser');

        $dayDB = self::getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());
        $this->assertTrue($dayDB->canUserGo($user));
    }

    /**
     * Der Admin Acceptiert den User
     */
    public function testAdminSignOffUser()
    {
        /**
         * @var User   $user
         * @var User   $user2
         * @var Day    $day
         * @var Day    $dayDB
         */
        list($day, $user, $user2) = $this->createDayTowUsers();

        $this->saveInDb([$day, $user, $user2]);

        $url = $this->_getUrl('trolley_agenda_calendar_adminsignoffuser', [
            'day' => $day->getId(),
            'user' => $user->getId(),
        ]);

        $client = static::createClient();
        $this->loginAsAdmin($client);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_adminacceptuser');

        $dayDB = self::getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $usernames = $dayDB->getTaUsers()->map(function($user) {return $user->getUsername();});
        $this->assertNotContains($user->getUsername(), $usernames);
    }

    public function testCloseDay()
    {
        $day = $this->createOneDay('2014-10-22');
        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_admincloseday', [
            'day' => $day->getId(),
        ]);

        $client = static::createClient();
        $this->loginAsAdmin($client);
        $client->request('POST', $url, ['message' => '#@Kreiskongress_phpunitTest-#@']);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_admincloseday');
        $dayDB = self::getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $this->assertTrue($dayDB->isDayClosed());
        $flashMessage = $this->_translate('page.calendar.admin_day_closed');
        $this->assertContains($flashMessage, $client->getResponse()->getContent());
    }

    public function testCloseDayWihtOutMessage()
    {
        $day = $this->createOneDay('2014-10-22');
        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_admincloseday', [
            'day' => $day->getId(),
        ]);

        $client = static::createClient();
        $this->loginAsAdmin($client);
        $client->request('POST', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_admincloseday');

        $flashMessage = $this->_translate('page.calendar.admin_empty_closed_message');
        $this->assertContains($flashMessage, $client->getResponse()->getContent());
    }

    public function testOpenDay()
    {
        $day = $this->createOneDay('2014-10-22');
        $day->closeDayWithMessage('#@Kreiskongress_phpunitTest-#@');
        $this->saveInDb([$day]);

        $url = $this->_getUrl('trolley_agenda_calendar_adminopenday', [
            'day' => $day->getId(),
        ]);

        $client = static::createClient();
        $this->loginAsAdmin($client);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Seite konnte nicht auf gerufen werden: (' . $client->getResponse()->getStatusCode() . ') trolley_agenda_calendar_adminopenday');
        $dayDB = self::getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $this->assertFalse($dayDB->isDayClosed());
        $flashMessage = $this->_translate('page.calendar.admin_day_open_agean');
        $this->assertContains($flashMessage, $client->getResponse()->getContent());
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

    protected function _translate($textId)
    {
        $kernel = self::$kernel->getContainer();
        /** @var \Symfony\Component\Translation\DataCollectorTranslator  $tr */
        $tr = $kernel->get('translator');
        return $tr->trans($textId);

    }

}