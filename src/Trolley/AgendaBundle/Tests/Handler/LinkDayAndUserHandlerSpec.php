<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Tests\Trolley\AgendaBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\HistoryService;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Tests\phpunit_utils\MockDay;
use Trolley\AgendaBundle\Tests\phpunit_utils\MockUser;

/**
 * @mixin \Trolley\AgendaBundle\Handler\LinkDayAndUserHandler
 */
class LinkDayAndUserHandlerSpec extends ObjectBehavior
{

    public function let(ObjectManager $objectManager)
    {
        $this->beConstructedWith($objectManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Handler\LinkDayAndUserHandler');
    }

    /**
     * Verlinkt den User zum Datum
     */
    public function it_can_link_user_to_date(
        ObjectManager $objectManager,
        User $user,
        Day $day,
        HistoryService $historyService
    ) {
        MockDay::Day($day, 'now');
        MockUser::User($user);

        $objectManager->persist(Argument::any())->shouldBeCalled();

        $day->addUser(Argument::any())->shouldBeCalled();
        $user->addDay(Argument::any())->shouldBeCalled();
        $user->getHistoryService()->shouldBeCalled()->willReturn($historyService);
        $historyService->addDate(Argument::any())->shouldBeCalled();

        $this->addUserToDay($user, $day);
    }
}
