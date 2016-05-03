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
use Trolley\AgendaBundle\Tests\phpunit_utils\MockDay;

/**
 * @mixin \Trolley\AgendaBundle\Entity\HistoryService
 */
class HistoryServiceSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Entity\HistoryService');
    }

    public function it_can_add_new_date(Day $day)
    {
        MockDay::Day($day, '+1 Day');

        $this->addDate($day)->shouldReturn(true);
        $this->addDate($day)->shouldReturn(false);
    }

    public function it_can_say_tow_passt_dates_and_on_forward_dates(
        Day $pastDay1,
        Day $pastDay2,
        Day $pastDay2clone,
        Day $forwardDay
    ) {
        MockDay::Day($pastDay1, '-2 Day');
        MockDay::Day($pastDay2, '-5 Day');
        MockDay::Day($pastDay2clone, '-5 Day');
        $pastDay2clone->getId()->shouldNotBeCalled();
        MockDay::Day($forwardDay, '+2 Day');

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
        MockDay::Day($day1, '-2 Day');
        MockDay::Day($day2, '+5 Day');
        MockDay::Day($day2clone, '+5 Day');

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
        MockDay::Day($day1, '-2 Day');
        MockDay::Day($day2, '+5 Day');
        MockDay::Day($day3, '-5 Day');

        $this->getNumberPastDates()->shouldReturn(0);
        $this->getNumberforwardDates()->shouldReturn(0);

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
