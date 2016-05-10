<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Tests\Trolley\AgendaBundle\Handler;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\HistoryService;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\MockDay;
use Trolley\AgendaBundle\Tests\phpunit_utils\MockUser;

/**
 * @mixin \Trolley\AgendaBundle\Handler\DayAndUserRelationship
 */
class DayAndUserRelationshipSpec extends ObjectBehavior
{

    public function let(EntityManagerInterface $objectManager)
    {
        $objectManager->persist(Argument::any())->shouldBeCalled();
        $this->beConstructedWith($objectManager);
    }

    public function it_is_initializable(EntityManagerInterface $objectManager)
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Handler\DayAndUserRelationship');
        $objectManager->persist(Argument::any())->shouldNotBeCalled();
    }

    /**
     * Verlinkt den User zum Datum
     */
    public function it_can_link_user_to_date(
        User $user,
        Day $day,
        HistoryService $historyService
    ) {
        MockDay::Day($day, 'now');
        MockUser::User($user);

        $day->addUser(Argument::any())->shouldBeCalled();
        $user->addDay(Argument::any())->shouldBeCalled();
        $user->getHistoryService()->shouldBeCalled()->willReturn($historyService);
        $historyService->addDate(Argument::any())->shouldBeCalled();

        $this->addUserToDay($user, $day);
    }

    /**
     * Testet ob man den User wieder weg nehmen kann
     */
    public function it_can_remove_user_from_day(
        User $user,
        Day $day,
        HistoryService $historyService
    ) {
        MockDay::Day($day, 'now');
        MockUser::User($user);

        $day->removeUser($user)->shouldBeCalled();
        $user->removeDay($day)->shouldBeCalled();
        $user->getHistoryService()->shouldBeCalled()->willReturn($historyService);

        $this->removeUserFromDay($user, $day);

    }

    public function it_can_remove_all_user_from_day(
        User $user,
        User $user2,
        Day $day,
        HistoryService $historyService
    ) {
        MockDay::Day($day, 'now');
        MockUser::User($user, 'testuser1');
        MockUser::User($user2, 'testuser2');

        $day->removeUser(Argument::any())->shouldBeCalled();

        $user->removeDay(Argument::any())->shouldBeCalled();
        $user2->removeDay(Argument::any())->shouldBeCalled();
        $user->getHistoryService()->willReturn($historyService);
        $user2->getHistoryService()->willReturn($historyService);

        $day->getTaUsers()->shouldBeCalled()->willReturn([$user, $user2]);

        $this->removeAllUserFromDay($day);
    }
}
