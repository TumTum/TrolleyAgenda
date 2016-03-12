<?php

namespace Trolly\AgendaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Day
 *
 * @ORM\Table(name="day")
 * @ORM\Entity(repositoryClass="Trolly\AgendaBundle\Repository\DayRepository")
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
     * @ORM\Column(name="taMonth", type="date")
     */
    private $taMonth;

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
     * Set taMonth
     *
     * @param \DateTime $taMonth
     *
     * @return Day
     */
    public function setTaMonth($taMonth)
    {
        $this->taMonth = $taMonth;

        return $this;
    }

    /**
     * Get taMonth
     *
     * @return \DateTime
     */
    public function getTaMonth()
    {
        return $this->taMonth;
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
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($datestring = null)
    {
        if ($datestring !== null) {
            $date = date_create($datestring);
            $this->setTaDay($date);
        }
    }


}

