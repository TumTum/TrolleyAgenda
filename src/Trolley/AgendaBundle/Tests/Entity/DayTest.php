<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 29.03.16
 * Time: 20:46
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\Entity;


use Trolley\AgendaBundle\Entity\Day;

class DayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test ob das Object ein Datum zurück gibt
     */
    public function testDayToString()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('2016-10-20', (string)$day);
    }

    /**
     * Testet das format
     */
    public function testFormatDate()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('10-2016~20', $day->format('m-Y~d'));
    }

    /**
     * Testet das format
     */
    public function testFormatMonth()
    {
        $day = new Day('2016-10-20');

        $this->assertEquals('October', $day->getMonthName());
    }
}
