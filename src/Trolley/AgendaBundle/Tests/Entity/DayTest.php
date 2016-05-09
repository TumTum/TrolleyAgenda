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
     * Testet es mit der DB den Tag mit den User
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
