<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:04
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Util;

use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\Month;
use Trolley\AgendaBundle\lib\CalculateMonth;

class MonthOverview extends \ArrayIterator
{

    /**
     * @var array
     */
    private $daysList = [];

    /**
     * Die Anzahl der Monate
     *
     * @param integer $count anzahl der Monate im Voraus
     *
     * @return $this
     */
    public function createAheadMonth($count)
    {
        $calculateMonth = new CalculateMonth('@'.time());

        for($month_add = 0; $month_add < $count; $month_add++) {
            $Month = new Month();
            $Month->setMonth($calculateMonth->monthToAdd($month_add));
            $this[$Month->getMonthName()] = $Month;
        }

        return $this;
    }

    /**
     * Erstellt die Monate davor
     *
     * @param integer $count
     *
     * @return $this
     */
    public function createBeforMonth($count)
    {
        $calculateMonth = new CalculateMonth('@'.time());

        for($month_sub = 1; $month_sub <= $count; $month_sub++) {
            $Month = new Month();
            $Month->setMonth($calculateMonth->monthToMinus($month_sub));
            $this[$Month->getMonthName()] = $Month;
        }

        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getMonthByName($name)
    {
        return $this[$name];
    }

    /**
     * Füllt alle Monate mit den Tagen aus
     *
     * @param array $days
     */
    public function fillMonthWithDaysFor($days)
    {
        /** @var Month $month */
        foreach ($this as $month) {
            $month->fillDaysFor($days);
            $this->_addDayToList($month);
        }
    }

    /**
     * Gibt alle Days zurück die in den Monaten erstellt wurden
     *
     * @return array
     */
    public function getDaysList()
    {
        return $this->daysList;
    }

    /**
     * Macht einen Zusammenführung der DB mit den Tagen
     */
    public function mergeDaysWithDB()
    {
        /** @var \Trolley\AgendaBundle\Repository\DayRepository $repositoryEntityDay */
        $repositoryEntityDay = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:Day');
        $dbDays = $repositoryEntityDay->findDaysByMonth($this);

        $this->_persistMissingDays($dbDays);
        $this->_replaceGenareteDaysWithDBDays($dbDays);
    }

    /**
     * Sollten Generierte Days nicht in der DB vorhanden so werden Sie erstellt.
     *
     * @param array $dbDays
     */
    protected function _persistMissingDays(array $dbDays)
    {
        $genarateDays = $this->getDaysList();

        //Findet die nicht gespeicherte heraus.
        $persistInDB = array_udiff(
            $genarateDays,
            $dbDays,
            function(Day $a, Day $b) {
                return strcmp($a->getIdDate(), $b->getIdDate());
            }
        );

        if (!empty($persistInDB)) {
            $this->_persistDaysInDB($persistInDB);
        }
    }

    /**
     * Speichtert die Days in der DB ab
     *
     * @param array $saveInDB
     */
    protected function _persistDaysInDB(array $persistInDB)
    {
        $manager = $this->getDoctrine()->getManager();
        foreach ($persistInDB as $dayEntity) {
            $manager->persist($dayEntity);
        }
        $manager->flush();
    }

    /**
     * Ersetzt die Automaitsch erstellte Days durch die von der DB
     *
     * @param array $dbDays
     */
    protected function _replaceGenareteDaysWithDBDays(array $dbDays)
    {
        $isRefreshList     = false;
        $genarateDays      = $this->getDaysList();
        $dbDaysKeyFormated = $this->_reformatArrayToFindDaysFaster($dbDays);

        /** @var Day $day */
        foreach ($genarateDays as $day) {
            if ($day->getId() === null) {
                $id = $day->getIdDate();

                /** @var Month $month */
                $month = $this[$day->getMonthName()];
                $month->replaceDay($dbDaysKeyFormated[$id]);

                $isRefreshList = true;
            }
        }

        if ($isRefreshList) {
            $this->_refreshDayList();
        }
    }

    /**
     * Formatiert das Array neu mit Keys damit es schneller gefunden werden
     * um es zu ersetzten.
     *
     * @param array $dbDays
     *
     * @return array
     */
    protected function _reformatArrayToFindDaysFaster(array $dbDays)
    {
        $dbDaysKeyFormated = [];

        /** @var Day $day */
        foreach ($dbDays as $day) {
            $id = $day->getIdDate();
            $dbDaysKeyFormated[$id] = $day;
        }

        return $dbDaysKeyFormated;
    }

    /**
     * @param array $month
     */
    protected function _addDayToList(Month $month)
    {
        foreach ($month as $day) {
            $this->daysList[] = $day;
        }
    }

    /**
     * Erneuert die Liste.
     */
    protected function _refreshDayList()
    {
        $this->daysList = [];
        foreach ($this as $month) {
            $this->_addDayToList($month);
        }
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        global $kernel;
        return $kernel->getContainer()->get('doctrine');
    }
}