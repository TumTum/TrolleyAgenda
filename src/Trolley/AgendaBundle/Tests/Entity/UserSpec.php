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
use Trolley\AgendaBundle\Entity\HistoryService;

/**
 * @mixin \Trolley\AgendaBundle\Entity\User
 */
class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Entity\User');
    }

    public function it_can_say_numbers_of_past_dates(HistoryService $historyService)
    {
        $historyService->getNumberPastDates()->shouldBeCalled()->willReturn(3);
        $this->setHistoryService($historyService);
        $this->getNumberPastDates()->shouldReturn(3);
    }

    public function it_can_say_numbers_of_forward_dates(HistoryService $historyService)
    {
        $historyService->getNumberforwardDates()->shouldBeCalled()->willReturn(3);
        $this->setHistoryService($historyService);
        $this->getNumberforwardDates()->shouldReturn(3);
    }
}
