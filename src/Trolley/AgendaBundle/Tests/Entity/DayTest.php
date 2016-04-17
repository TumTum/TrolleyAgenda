<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 29.03.16
 * Time: 20:46
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\Entity;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntityTrait;
use Trolley\AgendaBundle\Tests\phpunit_utils\createUserDayRelationshipsTrait;

class DayTest extends KernelTestCase
{
    use autoClearEntityTrait;
    use createUserDayRelationshipsTrait;

    /**
     * Test ob das Object ein Datum zurück gibt Day
     */
    public function testDayToString()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('2016-10-20', (string)$day);
    }

    /**
     * Testet das format
     */
    public function testFormatDate()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('10-2016~20', $day->format('m-Y~d'));
    }

    /**
     * Testet das format
     */
    public function testFormatMonth()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('October', $day->getMonthName());
    }

    /**
     * Fügt einen User zum Day
     */
    public function testAddUserToDay()
    {
        /**
         * @var User $user
         * @var User $user2
         * @var User $userDB
         * @var Day $day
         * @var Day $dayDB
         */
        list($day, $user, $user2) = $this->createDayTowUsers();

        $this->saveInDb([$day, $user, $user2]);

        $dayRepository  = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $dayDB = $dayRepository->find($day->getId());
        $this->assertCount(2, $dayDB->getTaUsers());

        foreach ($dayDB->getTaUsers() as $userDB) {
            $this->assertContains('testuser', $userDB->getUsername());
        }
    }

    /**
     * Löscht den User vom Tag wieder
     */
    public function testRemoveUserFromDay()
    {
        /**
         * @var User $user
         * @var User $user2
         * @var User $userFromDay
         * @var Day $day
         */
        list($day, $user, $user2) = $this->createDayTowUsers();

        $day->removeUser($user);

        foreach ($day->getTaUsers() as $userFromDay) {
            $this->assertNotEquals($user->getUsername(), $userFromDay->getUsername());
        }
    }

    /**
     * Prüft ob der User gehen darf
     */
    public function testUserCanNotGo()
    {
        $day = new Day('2014-02-03');

        $user = new User();
        $user->setUsername('UserCanNotGo');

        $this->assertFalse($day->canUserGo($user));
    }

    /**
     * Der User kann gehen
     */
    public function testUserCanGo()
    {
        $day = new Day('2014-02-03');

        $user = new User();
        $user->setUsername('UserCanGo');

        $day->userAcceptToGo($user);

        $this->assertTrue($day->canUserGo($user));
    }

    /**
     * Der User kann nicht gehen
     *
     * @covers Trolley\AgendaBundle\Entity\Day::userCancelToGo
     */
    public function testCancelUserToGo()
    {
        $day = new Day('2014-02-03');

        $user = new User();
        $user->setUsername('UserCanGo');

        $day->userAcceptToGo($user);
        $day->userCancelToGo($user);

        $this->assertFalse($day->canUserGo($user));
    }

    /**
     * Der User konnte gehen, wurde gecancelt.
     * Daraufhin bewarb er sich erneut, und bekommt den nicht angenommen Status
     */
    public function testUsersCouldGoWasCanceledCompetitionRenewed()
    {
        $day = new Day('2014-02-03');

        $user = new User();
        $user->setUsername('UserCanGo');

        $day->addUser($user);
        $day->userAcceptToGo($user);

        $day->removeUser($user);
        $day->addUser($user);

        $this->assertFalse($day->canUserGo($user));

    }

    public function testDoubleUserInDay()
    {
        $day = $this->createOneDay();
        $user = $this->createUser('doubleuser');

        $day->addUser($user);
        $day->addUser($user);

        $this->assertCount(1, $day->getTaUsers());
    }

    /**
     * Test ob man das Construkt in der DB speichern kann
     */
    public function testAcceptUserToGoPresetInDB()
    {
        /**
         * @var User $user
         * @var User $user2
         * @var Day $day
         * @var Day $dayDB
         */
        list($day, $user, $user2) = $this->createDayTowUsers();

        $day->userAcceptToGo($user);
        $day->userAcceptToGo($user2);

        $this->saveInDb([$day, $user, $user2]);

        $dayRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $dayDB = $dayRepository->find($day->getId());

        $this->assertTrue($dayDB->canUserGo($user2));
    }
}
