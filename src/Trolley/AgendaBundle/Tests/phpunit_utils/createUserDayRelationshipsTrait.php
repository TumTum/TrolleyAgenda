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

trait createUserDayRelationshipsTrait
{
    /**
     * @return Day
     */
    protected function createOneDay($date = "2014-10-22")
    {
        return new Day($date);
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function createUser($username = 'testuser', $admin = false)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username.'@localhost');
        $user->setPlainPassword($username.'passwort');
        $user->setEnabled(true);
        if ($admin) {
            $user->addRole('ROLE_ADMIN');
        }
        return $user;
    }

    /**
     * Erstellt einen User der mit zwei Day verkünft wurde.
     *
     * @covers \Trolley\AgendaBundle\Tests\Entity\UserTest::testUserToDayInverse
     *
     * @return array [User, Day, Day2]
     */
    protected function createUserInTowDays()
    {
        $user = $this->createUser();

        $day  = $this->createOneDay("2014-10-22");
        $day->addUser($user);

        $day2  = $this->createOneDay("2014-10-21");
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
        $user  = $this->createUser('testuser');
        $user2 = $this->createUser('testuser2');

        $day = $this->createOneDay("2014-10-22");
        $day->addUser($user);
        $day->addUser($user2);

        return [$day, $user, $user2];
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