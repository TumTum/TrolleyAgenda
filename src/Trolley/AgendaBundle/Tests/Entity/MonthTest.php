<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:57
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\Entity;


use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\Month;

class MonthTest extends \PHPUnit_Framework_TestCase
{
    public function testAsArrayAccessArray()
    {
        $month = new Month();

        $month[] = "Zahl 0";
        $month[] = "Zahl 1";
        $month['Name'] = "Tobias";

        $this->assertArrayHasKey('0', $month);
        $this->assertArrayHasKey('Name', $month);

        $this->assertEquals("Zahl 0", $month[0]);
        $this->assertEquals("Zahl 1", $month[1]);
        $this->assertEquals($month['Name'], 'Tobias');

        $month[0] = "Geaendert Zahl 0";
        $this->assertEquals("Geaendert Zahl 0", $month[0]);
    }

    public function testAsCountableArray()
    {
        $month = new Month();

        $month[] = "Tag 1";
        $month[] = "Tag 2";
        $month[] = "Tag 3";
        $month[] = "Tag 4";

        $this->assertEquals(4, count($month));
    }

    public function testAsIteratorArray()
    {
        $month = new Month();

        $month[] = "Tag 1";
        $month[] = "Tag 2";
        $month[] = "Tag 3";
        $month[] = "Tag 4";
        $month[] = "Tag 5";

        $counter = 1;

        foreach ($month as $day) {
            $this->assertEquals("Tag ".$counter++, $day);
        }
    }

    public function testSetDate()
    {
        $month = new Month();

        $date = date_create('now');
        $month->setDate($date);

        $this->assertEquals($date, $month->getDate());
    }

    public function testSetMonthDate()
    {
        $month = new Month();

        $month->setMonth('+1 Month');

        $expected = date_create('+1 Month')->format('F');

        $this->assertEquals($expected, $month->getDate()->format('F'));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function testSetMonthDateError()
    {
        $month = new Month();
        $month->setMonth('+1 Monate in Deutsch');
    }

    public function testGetMonthName()
    {
        $month = new Month();

        $month->setMonth('+2 Month');
        $expected = date_create('+2 Month')->format('F');

        $this->assertEquals($expected, $month->getMonthName());
    }

    /**
     * @return array
     */
    public function ListOfWeeks()
    {
        return [ ['Monday'],
                 ['Tuesday'],
                 ['Wednesday'],
                 ['Thursday'],
                 ['Friday'],
                 ['Saturday'],
                 ['Sunday']
        ];
    }

    /**
     * @dataProvider ListOfWeeks
     */
    public function testFillDay($week)
    {
        $monthyear = date_create()->format('F Y');

        $month_start = date_create("first {$week} of {$monthyear}");
        $month_end   = date_create("last {$week} of {$monthyear}");

        $plus7days = 604800; // 86400 * 7

        $days = [];
        for ($i = $month_start->format('U');
             $i <= $month_end->format('U');
             $i += $plus7days) {
            $days[] = date('l d-m-y', $i);
        }
        $days[] = date('l d-m-y', $month_end->format('U'));

        $month = new Month();
        $month->setMonth('this Month');
        $month->fillDaysOfWeek($week);

        foreach ($month as $date) {
            $this->assertEquals($week, $date->getTaDay()->format("l"));
            $this->assertContains($date->getTaDay()->format("l d-m-y"), $days);
        }
    }

    public function testSortDayEarlier()
    {
        $month = new Month();

        $earlierDay = new Day('2016-10-20');
        $laterDay = new Day('2016-10-21');

        $actual = $month->sortByDay($earlierDay, $laterDay);

        $this->assertEquals(-1, $actual);
    }

    public function testSortDayLater()
    {
        $month = new Month();

        $earlierDay = new Day('2016-10-20');
        $laterDay = new Day('2016-10-21');

        $actual = $month->sortByDay($laterDay, $earlierDay);

        $this->assertEquals(1, $actual);
    }

    public function testSortDaySame()
    {
        $month = new Month();

        $earlierDay = new Day('2016-10-20');
        $laterDay = new Day('2016-10-20');

        $actual = $month->sortByDay($laterDay, $earlierDay);

        $this->assertEquals(0, $actual);
    }

    public function testGetAllDayForSQL()
    {
        $month = new Month();

        $month[] = new Day('2016-10-20');
        $month[] = new Day('2016-10-21');
        $month[] = new Day('2016-10-22');

        $this->assertEquals("'2016-10-20','2016-10-21','2016-10-22'", $month->getSQLDates());

    }
}
