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
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@localhost');
        $user->setPlainPassword('testpasswort');

        $user2 = new User();
        $user2->setUsername('testuser2');
        $user2->setEmail('testuser2@localhost');
        $user2->setPlainPassword('testpasswort2');

        $day  = new Day("2016-10-22");
        $day->addUser($user);
        $day->addUser($user2);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($day);
        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();

        self::bootKernel();

        $dayRepository  = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $dayDB = $dayRepository->find($day->getId());
        $this->assertCount(2, $dayDB->getTaUsers());

        $userRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User');
        $user2db = $userRepository->findOneBy(['username' => 'testuser2']);

        $dayFromUser = $user2db->getDays();

        $this->assertEquals($day->getIdDate(), $dayFromUser[0]->getIdDate());
    }
}
