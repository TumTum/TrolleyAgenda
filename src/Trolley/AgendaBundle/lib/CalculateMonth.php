<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\lib;

/**
 * Class CalculateMonth
 *
 * Diese Klasse zählt die Monate immer vom ersten Tag.
 * und nicht vom relativen Tag des heutigen Monats.
 *
 * z.B. 29. Jan + 1 Monat = 01. März, weil es keinen 29. Feb gibt.
 */
class CalculateMonth
{

    /**
     * @var \DateTime
     */
    protected $actualMonth;

    /**
     * CalculateMonth constructor.
     */
    public function __construct($startDate)
    {
        $dateTime = date_create($startDate);

        if ($dateTime == false) {
            throw new \Exception('Date string is wrong: '. $startDate . ' - ' . json_encode(date_get_last_errors()));
        }

        $this->actualMonth = date_create('01-'.$dateTime->format('m-Y'));
    }

    /**
     * @return \DateTime
     */
    public function getActualMonth()
    {
        return $this->actualMonth;
    }

    /**
     * Zaehlt Monate hinzu
     *
     * @param $addMonth
     * @return \DateTime
     */
    public function monthToAdd($addMonth)
    {
        return $this->calMonth('add', $addMonth);
    }

    /**
     * Zählt Monate ab
     *
     * @param $minMonth
     * @return \DateTime
     */
    public function monthToMinus($minMonth)
    {
        return $this->calMonth('sub', $minMonth);
    }

    /**
     * @param $minMonth
     * @return \DateTime
     */
    protected function calMonth($func, $minMonth)
    {
        $month = clone $this->actualMonth;
        $month->$func(new \DateInterval("P{$minMonth}M"));
        return $month;
    }
}
