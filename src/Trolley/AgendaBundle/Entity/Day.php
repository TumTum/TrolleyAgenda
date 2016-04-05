<?php

namespace Trolley\AgendaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Prophecy\Exception\InvalidArgumentException;



/**
 * Day
 *
 * @ORM\Table(
 *     name="day",
 *     uniqueConstraints=@ORM\UniqueConstraint(name="OnlyOneDateAllow",columns={"taDay"})
 * )
 * @ORM\Entity(repositoryClass="Trolley\AgendaBundle\Repository\DayRepository")
 *
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
     * @ORM\Column(name="taDay", type="datetime")
     */
    private $taDay;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="day")
     */
    private $taUsers;

    /**
     * @var int
     *
     * @ORM\Column(name="taIsAccept", type="smallint", nullable=true)
     */
    private $taIsAccept;

    /**
     * Id nach Datum
     * @var string
     */
    private $idDate = "";

    /**
     * @var string
     */
    private $monthName = "";

    /**
     * Ist das datum Format für das Array key des $daysList
     */
    const idDateFormat = "YmdHi";


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
     * @return string
     */
    public function getIdDate()
    {
        if ($this->idDate == '' && !empty($this->taDay)) {
            $this->idDate = $this->taDay->format(self::idDateFormat);
        }
        return $this->idDate;
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
        $this->idDate = $taDay->format(self::idDateFormat);
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
     * Add User To Dat
     */
    public function addUser(User $user)
    {
        $user->addDay($this);
        $this->taUsers->add($user);
    }

    public function removeUser(User $user)
    {
        $this->taUsers->removeElement($user);
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
     * Gibt das Formatierte Datum zurück
     *
     * @param $format
     *
     * @return string
     */
    public function format($format)
    {
        return $this->getTaDay()->format($format);
    }

    /**
     * @return string
     */
    public function getMonthName()
    {
        if ($this->monthName == '' && !empty($this->taDay)) {
            $this->monthName = $this->taDay->format('F');
        }

        return $this->monthName;
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

        $this->taUsers = new ArrayCollection();
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->getTaDay()->format("Y-m-d");
    }

}

