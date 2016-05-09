<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Tests\Trolley\AgendaBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trolley\AgendaBundle\Entity\User;

/**
 * @mixin \Trolley\AgendaBundle\Entity\Day
 */
class DaySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('2016-10-20');
    }
    
    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Entity\Day');
    }

    public function it_is_a_string()
    {
        $this->__toString()->shouldReturn('2016-10-20');
    }

    public function it_format_date()
    {
        $this->format('m-Y~d')->shouldReturn('10-2016~20');
    }

    public function it_say_me_month_name()
    {
        $this->getMonthName()->shouldReturn('October');
    }

    public function it_user_add_to_day(User $user, User $user2)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('Username');
        $user2->getUsername()->shouldBeCalled()->willReturn('Username2');

        $this->addUser($user);
        $this->addUser($user);
        $this->addUser($user);
        $this->addUser($user2);
        $this->addUser($user2);

        $this->getTaUsers()->shouldHaveCount(2);
    }

    public function it_remove_user_from_day(User $user, User $user2)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('Username');
        $user2->getUsername()->shouldBeCalled()->willReturn('Username2');

        $this->addUser($user);
        $this->addUser($user2);
        $this->removeUser($user);

        $this->getTaUsers()->shouldHaveCount(1);
    }

    public function it_user_can_not_go(User $user)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('UserCanNotGo');
        $this->canUserGo($user)->shouldReturn(false);
    }

    public function it_user_accept_to_go(User $user)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('UserCanGo');
        $this->userAcceptToGo($user);
        $this->canUserGo($user)->shouldReturn(true);
    }

    public function it_user_cancel_to_go(User $user)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('UserCanGo');
        $this->userAcceptToGo($user);

        $this->userCancelToGo($user);

        $this->canUserGo($user)->shouldReturn(false);
    }

    /**
     * Der User konnte gehen, wurde gecancelt.
     * Daraufhin bewarb er sich erneut, und bekommt den nicht angenommen Status
     */
    public function it_user_removed_so_next_time_is_autmatic_accept_to_go(User $user)
    {
        $user->getUsername()->shouldBeCalled()->willReturn('UserCanGo');

        $this->addUser($user);
        $this->userAcceptToGo($user);

        $this->removeUser($user);
        $this->addUser($user);

        $this->canUserGo($user)->shouldReturn(false);
    }
}
