<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 02.04.16
 * Time: 09:59
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\Repository;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\VarDumper\VarDumper;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Util\MonthOverview;

class DayRepositoryTest extends KernelTestCase
{

    /**
     * @var array
     */
    protected $dbDays = [];

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        self::bootKernel();
    }


    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        $manager = $this->getDoctrine()->getManager();
        foreach ($this->dbDays as $day) {
            $manager->remove($day);
        }
        $manager->flush();
        $this->dbDays = [];
        parent::tearDown();
    }

    /**
     * Findet alle Tage SQL Auruf
     *
     * @covers \Trolley\AgendaBundle\Repository\DayRepository::findDaysByDate
     */
    public function testFindDaysByDate()
    {
        /** @var \Trolley\AgendaBundle\Repository\DayRepository $DayRepository */
        $DayRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');

        $sameDateDiffrentTime = new Day("+1 Day");
        $sameDateDiffrentTime->getTaDay()->add(new \DateInterval('PT1H'));

        $newGenarate = [
            new Day("+1 Day"),
            $sameDateDiffrentTime,
            new Day("+3 Day"),
            new Day("+4 Day"),
        ];

        $manager = $this->getDoctrine()->getManager();
        foreach ($newGenarate as $day) {
            $dbDay = clone $day;
            $this->dbDays[] = $dbDay;
            $manager->persist($dbDay);
        }
        $manager->flush();

        //Testet die funktion
        $days = $DayRepository->findDaysByDate($newGenarate);

        $expect = implode('.', $this->dbDays);
        $actual = implode('.', $days);

        $this->assertEquals($expect, $actual);
    }

    /**
     * Findet alle Tage SQL Auruf
     *
     * * @covers \Trolley\AgendaBundle\Repository\DayRepository::findDaysByMonth
     */
    public function testFindDaysByMonth()
    {
        /** @var \Trolley\AgendaBundle\Repository\DayRepository $DayRepository */
        $DayRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $newGenarate = [
            new Day("+1 Month"),
            new Day("+1 Day"),
            new Day("+2 Day"),
            new Day("+3 Day"),
        ];

        $manager = $this->getDoctrine()->getManager();
        foreach ($newGenarate as $day) {
            $dbDay = clone $day;
            $this->dbDays[] = $dbDay;
            $manager->persist($dbDay);
        }
        $manager->flush();

        $monthOverview = new MonthOverview();
        $monthOverview->createAheadMonth(2);

        //Testet die Funktion
        $days = $DayRepository->findDaysByMonth($monthOverview);

        $expect = array_map(function($item) {return $item->getTaDay()->format("Y-m");}, $newGenarate);
        $actual = array_map(function($item) {return $item->getTaDay()->format("Y-m");}, $days);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return self::$kernel->getContainer()->get('doctrine');
    }
}
