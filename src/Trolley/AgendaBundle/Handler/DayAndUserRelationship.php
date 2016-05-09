<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Entity\Day;

/**
 * Class LinkDayAndUserHandler
 */
class DayAndUserRelationship
{

    /**
     * @var EntityManagerInterface
     */
    private $_doctrineManager = null;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->setDoctrineManager($objectManager);
    }

    /**
     * Fügt dem Tag einen User hinzu
     *
     * @param User $user
     * @param Day  $day
     */
    public function addUserToDay(User $user, Day $day)
    {
        $day->addUser($user);
        $user->addDay($day);

        $historyService = $user->getHistoryService();
        $historyService->addDate($day);

        $this->getDoctrineManager()->persist($day);
        $this->getDoctrineManager()->persist($user);
        $this->getDoctrineManager()->persist($historyService);
    }

    /**
     * Enfernt den User vom Tag
     *
     * @param User $user
     * @param Day  $day
     */
    public function removeUserFromDay(User $user, Day $day)
    {
        $day->removeUser($user);
        $user->removeDay($day);

        $historyService = $user->getHistoryService();
        $historyService->removeDate($day);

        $this->getDoctrineManager()->persist($day);
        $this->getDoctrineManager()->persist($user);
        $this->getDoctrineManager()->persist($historyService);
    }

    /**
     * Löscht alle User von diesem Tag
     *
     * @param Day $day
     */
    public function removeAllUserFromDay(Day $day)
    {
        $users = $day->getTaUsers();
        foreach ($users as $user) {
            $this->removeUserFromDay($user, $day);
        }
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getDoctrineManager()
    {
        return $this->_doctrineManager;
    }

    /**
     * @param EntityManagerInterface $doctrineManager
     *
     * @return DayAndUserRelationship
     */
    protected function setDoctrineManager($doctrineManager)
    {
        $this->_doctrineManager = $doctrineManager;

        return $this;
    }
}
