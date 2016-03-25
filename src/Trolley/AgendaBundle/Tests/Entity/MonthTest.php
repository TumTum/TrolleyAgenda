<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:57
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\Entity;


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

}
