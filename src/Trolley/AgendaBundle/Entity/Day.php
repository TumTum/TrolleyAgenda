<?php

namespace Trolley\AgendaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prophecy\Exception\InvalidArgumentException;

/**
 * Day
 *
 * @ORM\Table(name="day")
 * @ORM\Entity(repositoryClass="Trolley\AgendaBundle\Repository\DayRepository")
 */
class Day
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="taDay", type="date")
     */
    private $taDay;

    /**
     * @var array
     *
     * @ORM\Column(name="taUsers", type="array", nullable=true)
     */
    private $taUsers;

    /**
     * @var int
     *
     * @ORM\Column(name="taIsAccept", type="smallint", nullable=true)
     */
    private $taIsAccept;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set taDay
     *
     * @param \DateTime $taDay
     *
     * @return Day
     */
    public function setTaDay($taDay)
    {
        $taDay->setTime(0,0,0);
        $this->taDay = $taDay;

        return $this;
    }

    /**
     * Get taDay
     *
     * @return \DateTime
     */
    public function getTaDay()
    {
        return $this->taDay;
    }

    /**
     * Set taUsers
     *
     * @param array $taUsers
     *
     * @return Day
     */
    public function setTaUsers($taUsers)
    {
        $this->taUsers = $taUsers;

        return $this;
    }

    /**
     * Get taUsers
     *
     * @return array
     */
    public function getTaUsers()
    {
        return $this->taUsers;
    }

    /**
     * Set taIsAccept
     *
     * @param integer $taIsAccept
     *
     * @return Day
     */
    public function setTaIsAccept($taIsAccept)
    {
        $this->taIsAccept = $taIsAccept;

        return $this;
    }

    /**
     * Get taIsAccept
     *
     * @return int
     */
    public function getTaIsAccept()
    {
        return $this->taIsAccept;
    }

    /**
     * Day constructor.
     *
     * @param null $datestring
     */
    public function __construct($datestring = null)
    {
        if ($datestring !== null) {
            if (is_scalar($datestring)) {
                $date = date_create($datestring);
            } elseif ($datestring instanceof \DateTime ) {
                $date = $datestring;
            } else {
                throw new InvalidArgumentException('It must be a String or DateTime object.');
            }

            $this->setTaDay($date);
        }
    }


}

