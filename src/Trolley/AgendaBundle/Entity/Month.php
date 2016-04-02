<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:55
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;

class Month extends \ArrayIterator
{

    /**
     * Datum diesen Monats
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @param string $time erstellt am Namen den Monat
     *
     * @return bool
     */
    public function setMonth($time)
    {
        $date = date_create($time);

        if ($date == false) {
            throw new Exception('Date string is wrong: '. $time . ' - ' . json_encode(date_get_last_errors()));
        }

        $this->setDate($date);
    }

    /**
     * Gibt den Monats Namen zuruck
     *
     * @return string
     */
    public function getMonthName()
    {
        if ($this->getDate() == null) {
            throw new Exception('No Date is set');
        }

        return $this->getDate()->format('F');
    }

    /**
     * Füllt die Tage der Wochen
     * @param $array
     */
    public function fillDaysFor($array)
    {
        array_map('self::fillDaysOfWeek', $array);
    }

    /**
     * Füllt die Wochentage diesen Monats auf
     *
     * @param $week
     */
    public function fillDaysOfWeek($week)
    {
        $month_year = $this->getDate()->format('F Y');

        $month_start = date_create("first {$week} of {$month_year}");
        $thisMonth = $month_start->format('m');

        $plus7days = new \DateInterval("P7D");
        
        while ($month_start->format('m') == $thisMonth) {
            $this->_addDay($month_start);
            $month_start->add($plus7days);
        }

        $this->asort();
    }

    /**
     * Fügt einen Tag hinzu.
     *
     * @param $time
     */
    protected function _addDay($date)
    {
        $this[] = new Day(clone $date);
    }

    /**
     * Sort array by values
     *
     * @link  http://php.net/manual/en/arrayiterator.asort.php
     * @return void
     * @since 5.2.0
     */
    public function asort()
    {
        $this->uasort([$this, 'sortByDay']);
    }

    public function sortByDay(Day $a, Day $b)
    {
        $atime = $a->getTaDay()->getTimestamp();
        $btime = $b->getTaDay()->getTimestamp();

        if ($atime == $btime) {
            return 0;
        }
        
        return $atime < $btime ? -1 : 1;
    }

    /**
     * @param Day $day
     *
     * @return bool
     */
    public function replaceDay(Day $day)
    {
        if ($this->getMonthName() != $day->format('F')) {
            return false;
        }

        foreach ($this as &$internalDay) {
            if ($internalDay->getIdDate() == $day->getIdDate()) {
                $internalDay = $day;
                return true;
            }
        }

        return false;
    }

}