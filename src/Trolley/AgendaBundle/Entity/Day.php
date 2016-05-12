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
     * @ORM\ManyToMany(targetEntity="User", inversedBy="days")
     */
    private $taUsers;

    /**
     * @var array
     *
     * @ORM\Column(name="taAcceptUsers", type="json_array", nullable=true)
     */
    private $taAcceptUsers = [];

    /**
     * @var bool
     *
     * @ORM\Column(name="taClose", type="boolean")
     */
    private $taClose = false;

    /**
     * @var string
     *
     * @ORM\Column(name="taCloseMessage", type="string", length=255)
     */
    private $taCloseMessage = '';

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
     * @var bool
     */
    private $isDayBeforeToday = null;

    /**
     * Ist das datum Format für das Array key des $daysList
     */
    const idDateFormat = "YmdHi";


    /**
     * Day constructor.
     *
     * @param null $datestring
     */
    public function __construct($datestring = null)
    {
        $this->taUsers = new ArrayCollection();
        $this->initDay($datestring);
    }

    /**
     * Erstellt das Datum dieses Object
     *
     * @param $datestring
     */
    protected function initDay($datestring)
    {
        if ($datestring !== null) {
            if (is_scalar($datestring)) {
                $date = date_create($datestring);
            } elseif ($datestring instanceof \DateTime ) {
                $date = $datestring;
            } else {
                throw new InvalidArgumentException('It must be a String or DateTime object.');
            }

            $this->setConformTaDay($date);
        }
    }

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
    public function setTaDay(\DateTime $taDay)
    {
        $this->setConformTaDay($taDay);
        return $this;
    }

    /**
     * Setzt das Datum aber in ein richtiges format.
     *
     * @param \DateTime $taDay
     */
    protected function setConformTaDay(\DateTime $taDay)
    {
        $taDay->setTime(0,0,0);
        $this->taDay = $taDay;
        $this->idDate = $taDay->format(self::idDateFormat);
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
        if ($this->canAddUser($user)) {
            $this->taUsers->add($user);
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    protected function canAddUser(User $user)
    {
        $foundUser = $this->taUsers->exists(
            function($key, $element) use ($user) {
                return $user->getUsername() == $element->getUsername();
            }
        );

        return $foundUser == false;
    }

    public function removeUser(User $user)
    {
        $this->userCancelToGo($user);
        $this->taUsers->removeElement($user);
    }

    /**
     * @return ArrayCollection
     */
    public function getTaAcceptUsers()
    {
        return $this->taAcceptUsers;
    }

    /**
     * @param ArrayCollection $taAcceptUsers
     */
    public function setTaAcceptUsers($taAcceptUsers)
    {
        $this->taAcceptUsers = $taAcceptUsers;
    }

    /**
     * @return bool
     */
    protected function _getTaClose()
    {
        return $this->taClose;
    }

    /**
     * @param bool $taClose
     */
    protected function _setTaClose($taClose)
    {
        $this->taClose = $taClose;
    }

    /**
     * @return string
     */
    protected function _getTaCloseMessage()
    {
        return $this->taCloseMessage;
    }

    /**
     * @param string $taCloseMessage
     */
    protected function _setTaCloseMessage($taCloseMessage)
    {
        $this->taCloseMessage = $taCloseMessage;
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
     * Prüft ob der User gehen darf
     *
     * @return bool
     */
    public function canUserGo(User $user)
    {
        return (array_search($user->getUsername(), $this->taAcceptUsers) !== false);
    }

    /**
     * User darf gehen
     *
     * @param User $user
     */
    public function userAcceptToGo(User $user)
    {
        $this->taAcceptUsers[] = $user->getUsername();
    }

    /**
     * User kann geht doch nicht mit zum Trolley
     *
     * @param User $user
     */
    public function userCancelToGo(User $user)
    {
        $key = array_search($user->getUsername(), $this->taAcceptUsers);
        if ($key !== false) {
            unset($this->taAcceptUsers[$key]);
        }
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return $this->getTaDay()->format("Y-m-d");
    }

    /**
     * @param string $message
     */
    public function closeDayWithMessage($message)
    {
        $this->_setTaClose(true);
        $this->_setTaCloseMessage($message);
    }

    /**
     * @return string
     */
    public function getClosedMessage()
    {
        return $this->_getTaCloseMessage();
    }

    /**
     * @return bool
     */
    public function isDayClosed()
    {
        return $this->_getTaClose() == true;
    }

    /**
     * Öffnet den Tag
     */
    public function openDay()
    {
        $this->_setTaClose(false);
        $this->_setTaCloseMessage('');
    }

    public function isDayBeforeToday()
    {
        if ($this->isDayBeforeToday === null) {
            $dateIntervall = $this->getTaDay()->diff(new \DateTime('now'));
            $this->isDayBeforeToday = (
                $dateIntervall->invert == 0 && // liegt in der Vergangenheit
                $dateIntervall->days != 0 // und wenn es nicht heute ist
            );
        }

        return $this->isDayBeforeToday;

    }
}

