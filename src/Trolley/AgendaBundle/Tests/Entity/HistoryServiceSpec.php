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
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;

/**
 * @mixin \Trolley\AgendaBundle\Entity\HistoryService
 */
class HistoryServiceSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Entity\HistoryService');
    }

    public function it_can_add_new_date(Day $day, \DateTime $date)
    {
        $day->getTaDay()->shouldBeCalled()->willReturn($date);
        $day->getId()->shouldBeCalled()->willReturn(1);

        $this->addDate($day)->shouldReturn(true);
        $this->addDate($day)->shouldReturn(false);
    }

    public function it_can_say_tow_passt_dates_and_on_forward_dates(
        Day $pastDay1,
        Day $pastDay2,
        Day $pastDay2clone,
        Day $forwardDay
    ) {
        $datePast1 = new \DateTime('-2 Day');
        $datePast2 = new \DateTime('-5 Day');
        $dateForward = new \DateTime('+2 Day');

        $pastDay1->getTaDay()->shouldBeCalled()->willReturn($datePast1);
        $pastDay1->getId()->shouldBeCalled()->willReturn(1);
        $pastDay2->getTaDay()->shouldBeCalled()->willReturn($datePast2);
        $pastDay2->getId()->shouldBeCalled()->willReturn(2);
        $pastDay2clone->getTaDay()->shouldBeCalled()->willReturn($datePast2);
        $pastDay2clone->getId()->shouldNotBeCalled()->willReturn(3);
        $forwardDay->getTaDay()->shouldBeCalled()->willReturn($dateForward);
        $forwardDay->getId()->shouldBeCalled()->willReturn(4);

        $this->addDate($pastDay1);
        $this->addDate($pastDay2);
        $this->addDate($pastDay2clone);
        $this->addDate($forwardDay);

        $this->getNumberPastDates()->shouldReturn(2);
        $this->getNumberforwardDates()->shouldReturn(1);
    }

    public function it_can_remove_oneDate(
        Day $day1,
        Day $day2,
        Day $day2clone
    ) {
        $date1 = new \DateTime('-2 Day');
        $date2 = new \DateTime('+5 Day');

        $day1->getTaDay()->shouldBeCalled()->willReturn($date1);
        $day1->getId()->shouldBeCalled()->willReturn(1);
        $day2->getTaDay()->shouldBeCalled()->willReturn($date2);
        $day2->getId()->shouldBeCalled()->willReturn(2);
        $day2clone->getTaDay()->shouldBeCalled()->willReturn($date2);
        $day2clone->getId()->shouldBeCalled()->willReturn(3);

        $this->addDate($day1);
        $this->addDate($day2);
        $this->addDate($day2clone);

        $this->removeDate($day1)->shouldReturn(true);
        $this->removeDate($day2clone)->shouldReturn(false);
    }

    public function it_can_analyzing_caching(
        Day $day1,
        Day $day2,
        Day $day3
    ) {
        $date1 = new \DateTime('-2 Day');
        $date2 = new \DateTime('+5 Day');
        $date3 = new \DateTime('-5 Day');

        $this->getNumberPastDates()->shouldReturn(0);
        $this->getNumberforwardDates()->shouldReturn(0);

        $day1->getTaDay()->shouldBeCalled()->willReturn($date1);
        $day1->getId()->shouldBeCalled()->willReturn(1);
        $day2->getTaDay()->shouldBeCalled()->willReturn($date2);
        $day2->getId()->shouldBeCalled()->willReturn(2);
        $day3->getTaDay()->shouldBeCalled()->willReturn($date3);
        $day3->getId()->shouldBeCalled()->willReturn(3);

        $this->addDate($day1);
        $this->addDate($day3);
        $this->getNumberPastDates()->shouldReturn(2);

        $this->addDate($day2);
        $this->addDate(clone $day2);
        $this->getNumberforwardDates()->shouldReturn(1);

        $this->removeDate($day1);
        $this->removeDate(clone $day1);
        $this->getNumberPastDates()->shouldReturn(1);
        $this->getNumberforwardDates()->shouldReturn(1);

    }
}
