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
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntity;

class DayTest extends KernelTestCase
{
    use autoClearEntity;

    /**
     * Test ob das Object ein Datum zurück gibt     $day =
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
        $day  = new Day("2016-10-20");
        $user = new User();

        $user->setUsername('testuser');
        $user->setEmail('testuser@localhost');
        $user->setPlainPassword('testpasswort');

        $day->addUser($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($this->clearEntity($day));
        $manager->persist($this->clearEntity($user));
        $manager->flush();
        $manager->detach($day);
        $manager->detach($user);

        $dayDB = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day')->find($day->getId());

        $this->assertCount(1, $dayDB->getTaUsers());

        $users = $dayDB->getTaUsers();

        $this->assertEquals('testuser', $users[0]->getUsername());
    }
}
