<?php

namespace Trolley\AgendaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryService
 *
 * @ORM\Table(name="history_service")
 * @ORM\Entity(repositoryClass="Trolley\AgendaBundle\Repository\HistoryServiceRepository")
 */
class HistoryService
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
     * @var array
     *
     * @ORM\Column(name="logDates", type="json_array")
     */
    private $logDates = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedate", columnDefinition="timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
     */
    private $updatdate = 0;

    /**
     * The dates was going on
     *
     * @var int
     */
    private $_analysisPastDates = 0;

    /**
     * The dates he was go
     *
     * @var int
     */
    private $_analysisforwardDates = 0;

    /**
     * @var bool
     */
    private $_dateAnlysing = false;
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
     * Set logDates
     *
     * @param array $logDates
     *
     * @return HistoryService
     */
    public function setLogDates($logDates)
    {
        $this->logDates = $logDates;

        return $this;
    }

    /**
     * Get logDates
     *
     * @return array
     */
    public function getLogDates()
    {
        return $this->logDates;
    }

    /**
     * Set updatdate
     *
     * @param \DateTime $updatdate
     *
     * @return HistoryService
     */
    public function setUpdatdate($updatdate)
    {
        $this->updatdate = $updatdate;

        return $this;
    }

    /**
     * Get updatdate
     *
     * @return \DateTime
     */
    public function getUpdatdate()
    {
        return $this->updatdate;
    }

    /**
     * Fügt eine Datum hinzu
     *
     * @return bool
     */
    public function addDate(Day $date)
    {
        $successful  = false;
        $timestamp = $date->getTaDay()->getTimestamp();

        if (!isset($this->logDates[$timestamp])) {
            $this->logDates[$timestamp] = $date->getId();
            $this->newDateAnaysing();
            $successful = true;
        }

        return $successful;
    }

    /**
     * @param Day $day
     *
     * @return bool
     */
    public function removeDate(Day $day)
    {
        $id = $day->getId();
        $keys = array_search($id, $this->logDates);
        if ($keys !== false) {
            $this->newDateAnaysing();
            unset($this->logDates[$keys]);
            return true;
        }
        return false;
    }

    /**
     * The Date he was Going on
     *
     * @return int
     */
    public function getNumberPastDates()
    {
        $this->analyzingDates();
        return $this->_analysisPastDates;
    }

    /**
     * The Date he has forward
     * @return int
     */
    public function getNumberforwardDates()
    {
        $this->analyzingDates();
        return $this->_analysisforwardDates;
    }

    protected function analyzingDates()
    {
        if ($this->isDateAnlysing()) {
            return;
        }

        $today = new \DateTime('now');
        $todayTimestamp = $today->getTimestamp();

        $logDates = array_keys($this->logDates);

        foreach ($logDates as $timestamp) {
            if ($timestamp < $todayTimestamp) {
                $this->_analysisPastDates += 1;
            } else {
                $this->_analysisforwardDates += 1;
            }
        }

        $this->dateAnalyzed();
    }

    /**
     * @return boolean
     */
    protected function isDateAnlysing()
    {
        return $this->_dateAnlysing == true;
    }

    /**
     * @param boolean $dateAnlysing
     */
    protected function dateAnalyzed()
    {
        $this->_dateAnlysing = true;
    }

    /**
     * @param boolean $dateAnlysing
     */
    protected function newDateAnaysing()
    {
        $this->_analysisPastDates = 0;
        $this->_analysisforwardDates = 0;
        $this->_dateAnlysing = false;
    }
}

