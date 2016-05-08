<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.04.16
 * Time: 15:29
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Tests\Util;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Handler\DayAndUserRelationship;
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntityTrait;
use Trolley\AgendaBundle\Tests\phpunit_utils\createUserDayRelationshipsTrait;
use Trolley\AgendaBundle\Util\BulksUsersToDays;

class BulksUsersToDaysTest extends KernelTestCase
{
    use autoClearEntityTrait;
    use createUserDayRelationshipsTrait;

    /**
     * Anhand einer Liste werden Users zu den Tagen hinzugefügt fügt es mehere User dem
     */
    public function testBulksUserToDaysFormular()
    {
        $day             = $this->createOneDay();
        $day2            = $this->createOneDay("2014-10-23");
        $dayDouppleUser  = $this->createOneDay("2014-10-24");
        $user            = $this->createUser('testuser');
        $user2           = $this->createUser('testuser2');

        $dayDouppleUser->addUser($user);

        $this->saveInDb([$day, $day2, $dayDouppleUser, $user, $user2]);

        $formular = [
            'dayid_'.$day->getId() => [
                $user->getId(),
                $user2->getId()+333
            ],
            'dayid_'.$day2->getId() => [
                $user->getId(),
                $user2->getId(),
                'wrong_id'
            ],
            'dayid_'.$dayDouppleUser->getId() => [
                $user->getId(),
                $user2->getId()
            ],
            'dayid_'.($day2->getId()+333) => [
                2,
                3
            ],
            'error' => [
                1,
                2
            ]
        ];

        $manager = $this->getDoctrine()->getManager();

        $handler = new DayAndUserRelationship($manager);

        $bulksUsersToDays = new BulksUsersToDays($manager, $handler);
        $bulksUsersToDays->processForm($formular);
        $day2toSave = $bulksUsersToDays->getEntitys();

        $this->assertCount(3, $day2toSave, 'Item `dayid_+333` und `error` sind nicht vorhanden ');
        $this->assertEquals((string)$day, (string)$day2toSave[0]);
        $this->assertEquals((string)$day2, (string)$day2toSave[1]);
        $this->assertCount(1, $day2toSave[0]->getTaUsers(), 'User `+333` darf nicht vorhanden sein, weil es nicht exitiert');
        $this->assertCount(2, $day2toSave[1]->getTaUsers(), 'Die wrong_id ist falsch und darf nicht vorhanden sein');
        $this->assertCount(2, $day2toSave[2]->getTaUsers());
        $this->saveInDb($day2toSave);
    }
}
