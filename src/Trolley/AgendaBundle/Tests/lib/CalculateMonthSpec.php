<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Tests\Trolley\AgendaBundle\lib;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trolley\AgendaBundle\lib\CalculateMonth;

/**
 * @mixin \Trolley\AgendaBundle\lib\CalculateMonth
 */
class CalculateMonthSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith("29-01-2017");
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CalculateMonth::class);
    }

    public function it_can_give_me_fist_of_the_month()
    {
        $this->getActualMonth()->format("d-m-Y")->shouldBe("01-01-2017");
    }

    public function it_can_add_next_month_from_Jan_to_Feb()
    {
        $this->beConstructedWith("29-01-2017");
        $this->monthToAdd(1)->format("d-m-Y")->shouldBe("01-02-2017");;
    }

    public function it_can_add_next_month_from_Dez_to_Jan()
    {
        $this->beConstructedWith("24-12-2017");
        $this->monthToAdd(1)->format("d-m-Y")->shouldBe("01-01-2018");;
    }

    public function it_can_add_next_month_from_feb_to_mai()
    {
        $this->beConstructedWith("28-02-2017");
        $this->monthToAdd(3)->format("d-m-Y")->shouldBe("01-05-2017");;
    }

    public function it_can_minus_month_from_feb_to_feb()
    {
        $this->beConstructedWith("28-02-2017");
        $this->monthToMinus(1)->format("d-m-Y")->shouldBe("01-01-2017");;
    }

    public function it_can_minus_month_from_feb_to_last_year_feb()
    {
        $this->beConstructedWith("28-02-2017");
        $this->monthToMinus(12)->format("d-m-Y")->shouldBe("01-02-2016");;
    }

    public function it_can_give_a_time_iteger()
    {
        $this->beConstructedWith('@'.time());
        $this->getActualMonth()->shouldHaveType(\DateTime::class);
    }

    public function it_can_work_with_wrong_date()
    {
        $this->beConstructedWith(time());
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }
}
