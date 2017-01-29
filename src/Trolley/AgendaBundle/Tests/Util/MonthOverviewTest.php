<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:05
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Util\Tests;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\Month;
use Trolley\AgendaBundle\Util\MonthOverview;
use Symfony\Component\VarDumper\VarDumper;

class MonthOverviewTest extends KernelTestCase
{
    /**
     * @var array
     */
    protected $dayInDBToDelete = [];
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    /**
     * Test zwei Monate
     */
    public function testCreateTowMonth()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        $this->assertCount(2, $monthOverview);
    }

    /**
     * Test den zweiten Monat ob es wirklich stimmt
     */
    public function testCreateNextMonthWithName()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        $nextMonth = date_create('1-'.date_create()->format('m-Y'));
        $nextMonth->add(new \DateInterval('P1M'));

        $excepted = $nextMonth->format('F');
        $this->assertArrayHasKey($excepted, $monthOverview);
    }

    /**
     * Testet den letzten Monat
     */
    public function testCreateLastMonthWithName()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createBeforMonth(1);

        $thisMonth = date_create("last month")->format('F');
        $this->assertArrayHasKey($thisMonth, $monthOverview);
    }

    /**
     * Testen den Inhalt des Monats mit den Tagen
     */
    public function testFillMonthWihtEntry()
    {
        $weeks = ['Saturday', 'Monday'];

        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(1);

        $thisMonth = new Month();
        $thisMonth->setMonth(date_create('now'));

        $monthOverview->fillMonthWithDaysFor($weeks);

        $month = $monthOverview->getMonthByName($thisMonth->getMonthName());

        /** @var Day $day */
        foreach ($month as $day) {
            $week = $day->getTaDay()->format('l');
            $this->assertContains($week, $weeks);
        }
    }

    /**
     * Testet ob MonthOverview alle Tage hat
     */
    public function testDayListe()
    {
        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        $exceptCount = 0;

        $Month = new Month();
        $Month->setMonth(date_create('now'));
        $Month->fillDaysOfWeek('Saturday');
        $exceptCount += count($Month);

        $nextMonth = date_create('1-'.date_create()->format('m-Y'));
        $nextMonth->add(new \DateInterval('P1M'));

        $Month = new Month();
        $Month->setMonth($nextMonth);
        $Month->fillDaysOfWeek('Saturday');

        $exceptCount += count($Month);

        $monthOverview->fillMonthWithDaysFor(['Saturday']);

        $this->assertCount($exceptCount, $monthOverview->getDaysList());
    }

    /**
     * Erstellt alle Tage in der DB wenn sie nicht vor handen sind
     *
     * @covers Trolley\AgendaBundle\Util\MonthOverview::mergeDaysWithDB
     */
    public function testCreateDaysInDB()
    {
        global $kernel;
        $kernel = self::$kernel;

        //New 'Saturday' Days
        $expectMonthOverview = new MonthOverview();
        $expectMonthOverview->createAheadMonth(1);
        $expectMonthOverview->fillMonthWithDaysFor(['Saturday']);
        $expectMonthOverview->mergeDaysWithDB();

        //Add 'Monday' Days
        $expectMonthOverview = new MonthOverview();
        $expectMonthOverview->createAheadMonth(1);
        $expectMonthOverview->fillMonthWithDaysFor(['Monday', 'Saturday']);
        $expectMonthOverview->mergeDaysWithDB();

        $actualMonthOverview = new MonthOverview();
        $actualMonthOverview->createAheadMonth(1);
        $actualMonthOverview->fillMonthWithDaysFor(['Monday', 'Saturday']);

        /** @var \Trolley\AgendaBundle\Repository\DayRepository $dayRepository */
        $dayRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $actual = $dayRepository->findDaysByMonth($actualMonthOverview);

        $this->dayInDBToDelete = $actual;

        $this->assertGreaterThan(0, count($actual));
        $this->assertEquals($expectMonthOverview->getDaysList(), $actual);

    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return self::$kernel->getContainer()->get('doctrine');
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        $manager = self::getDoctrine()->getManager();
        foreach ($this->dayInDBToDelete as $Entity) {
            $manager->remove($Entity);
        }
        $manager->flush();

        parent::tearDown();
    }


}
