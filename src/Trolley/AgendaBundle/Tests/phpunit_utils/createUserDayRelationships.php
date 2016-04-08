<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 05.04.16
 * Time: 21:15
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;

trait createUserDayRelationships
{
    /**
     * Erstellt einen User der mit zwei Day verkünft wurde.
     *
     * @covers \Trolley\AgendaBundle\Tests\Entity\UserTest::testUserToDayInverse
     *
     * @return array [User, Day, Day2]
     */
    protected function createUserInTowDays()
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@localhost');
        $user->setPlainPassword('testpasswort');

        $day  = new Day("2014-10-22");
        $day->addUser($user);

        $day2  = new Day("2014-10-21");
        $day2->addUser($user);

        return [$user, $day, $day2];
    }

    /**
     * Erstellt einen Day mit zwei Users
     *
     * @covers Trolley\AgendaBundle\Tests\Entity\DayTest::testAddUserToDay
     *
     * @return array
     */
    protected function createDayTowUsers()
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@localhost');
        $user->setPlainPassword('testpasswort');

        $user2 = new User();
        $user2->setUsername('testuser2');
        $user2->setEmail('testuser2@localhost');
        $user2->setPlainPassword('testpasswort2');

        $day  = new Day("2014-10-22");
        $day->addUser($user);
        $day->addUser($user2);

        return [$day, $user, $user2];
    }

    /**
     * @return Day
     */
    protected function createOneDay()
    {
        return new Day("2014-10-22");
    }

    /**
     * @param array $Entitys
     */
    protected function saveInDb(array $entitys)
    {
        $manager = $this->getDoctrine()->getManager();
        foreach ($entitys as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();


        self::bootKernel();
        global $kernel;
        $kernel = self::$kernel;
    }
}