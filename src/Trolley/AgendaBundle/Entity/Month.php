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

    public function getMonthName()
    {
        if ($this->getDate() == null) {
            throw new Exception('No Date is set');
        }

        return $this->getDate()->format('F');
    }
}