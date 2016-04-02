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

class DayRepositoryTest extends KernelTestCase
{

    /**
     * @var array
     */
    protected $dbDays = [];

    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
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
        parent::tearDown();
    }

    /**
     * Findet alle Tage SQL Auruf
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

        $days = $DayRepository->findDaysByDate($newGenarate);

        $expect = implode('.', $this->dbDays);
        $actual = implode('.', $days);

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
