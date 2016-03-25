<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 19:04
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Util;


use Trolley\AgendaBundle\Entity\Month;

class MonthOverview extends \ArrayIterator
{

    /**
     * Die Anzahl der Monate
     *
     * @param integer $count anzahl der Monate im Voraus
     *
     * @return array
     */
    public function createAheadMonth($count)
    {
        for($month_add = 0; $month_add < $count; $month_add++) {
            $Month = new Month();
            $Month->setMonth("+" . $month_add . ' month');
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
        for($month_add = 1; $month_add <= $count; $month_add++) {
            $Month = new Month();
            $Month->setMonth("-" . $month_add . ' month');
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
     * @param array days
     */
    public function fillMonthWithDaysFor($days)
    {
        /** @var Month $month */
        foreach ($this as $month) {
            $month->fillDaysFor($days);
        }
    }

}