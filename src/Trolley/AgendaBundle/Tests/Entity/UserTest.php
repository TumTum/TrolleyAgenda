<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 05.04.16
 * Time: 21:03
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\VarDumper\VarDumper;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\autoClearEntityTrait;
use Trolley\AgendaBundle\Tests\phpunit_utils\createUserDayRelationshipsTrait;

class UserTest extends KernelTestCase
{
    use autoClearEntityTrait;
    use createUserDayRelationshipsTrait;

    /**
     * Wenn man dem Datum einen User gibt get es auch wieder Inverse
     */
    public function testUserToDayInverse()
    {
        /**
         * @var User $user
         * @var Day $day
         * @var Day $day2
         * @var Day $dayDB
         */
        list($user, $day, $day2) = $this->createUserInTowDays();

        $this->saveInDb([$user, $day, $day2]);

        $userRepository  = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User');
        $userDB = $userRepository->find($user->getId());

        $this->assertCount(2, $userDB->getDays());

        foreach ($userDB->getDays() as $dayDB) {
            $this->assertRegExp('/2014-10-(21|22)/', $dayDB->format('Y-m-d'));
        }
    }

    /**
     * Wenn am Day ein User wegenommen wurde so ist der User nur mit einem verkünpft
     */
    public function testRemoveUserToDayInverse()
    {
        /**
         * @var User $user
         * @var Day $day
         * @var Day $day2
         */
        list($user, $day, $day2) = $this->createUserInTowDays();

        $handler = $this->getDayAndUserRelationship();
        $handler->removeUserFromDay($user, $day2);

        $this->assertCount(1, $user->getDays());

        foreach ($user->getDays() as $dayInUser) {
            $this->assertNotEquals($day2->getIdDate(), $dayInUser->getIdDate());
        }
    }
}
