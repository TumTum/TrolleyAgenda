<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:05
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Util\Tests;


use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\Month;
use Trolley\AgendaBundle\Util\MonthOverview;
use Symfony\Component\VarDumper\VarDumper;

class MonthOverviewTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateTowMonth()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        $this->assertCount(2, $monthOverview);
    }

    public function testCreateNextMonthWithName()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        $thisMonth = date_create("+1 month")->format('F');
        $this->assertArrayHasKey($thisMonth, $monthOverview);
    }

    public function testCreateLastMonthWithName()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createBeforMonth(1);

        $thisMonth = date_create("last month")->format('F');
        $this->assertArrayHasKey($thisMonth, $monthOverview);
    }

    public function testFillMonthWihtEntry()
    {
        $weeks = ['Saturday', 'Monday'];

        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(1);

        $thisMonth = new Month();
        $thisMonth->setMonth("now");

        $monthOverview->fillMonthWithDaysFor($weeks);

        $month = $monthOverview->getMonthByName($thisMonth->getMonthName());

        /** @var Day $day */
        foreach ($month as $day) {
            $week = $day->getTaDay()->format('l');
            $this->assertContains($week, $weeks);
        }
    }
}
