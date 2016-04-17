<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.04.16
 * Time: 15:28
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Util;


use Doctrine\ORM\EntityManager;
use Trolley\AgendaBundle\Entity\Day;

class BulksUsersToDays
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $entitys = [];

    /**
     * @inheritDoc
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Verarbeitet die Formular
     */
    public function processForm(array $formular) {
        foreach ($formular as $dayid => $users) {
            if (preg_match('/^dayid_(\d+)$/', $dayid, $matchday)) {
                $this->addUsersToFoundDay($matchday[1], $users);
            }
        }
    }

    /**
     * Sucht den Tag und fügt User hinzu
     *
     * @param string $dayid
     * @param array $users
     */
    protected function addUsersToFoundDay($dayid, $users)
    {
        $day = $this->getDayRepository()->find($dayid);

        if ($day) {
            foreach ($users as $userid) {
                if (preg_match('/^\d+$/', $userid, $matchuser) ){
                    $this->addUserToDay($matchuser[0], $day);
                }
            }
            $this->entitys[] = $day;
        }
    }

    /**
     * Bei gefunden User wird er zum Tag hin zugefügt
     *
     * @param integer $userid
     * @param Day     $day
     */
    protected function addUserToDay($userid, Day $day)
    {
        $userRepository = $this->getUserRepository();
        $user = $userRepository->find($userid);
        if ($user) {
            $day->addUser($user);
        }
    }

    /**
     * @return \Trolley\AgendaBundle\Repository\DayRepository
     */
    protected function getDayRepository()
    {
        return $this->entityManager->getRepository('TrolleyAgendaBundle:Day');
    }

    /**
     * @return \Trolley\AgendaBundle\Repository\UserRepository
     */
    protected function getUserRepository()
    {
        return $this->entityManager->getRepository('TrolleyAgendaBundle:User');
    }

    /**
     * @return array
     */
    public function getEntitys()
    {
        return $this->entitys;
    }
}