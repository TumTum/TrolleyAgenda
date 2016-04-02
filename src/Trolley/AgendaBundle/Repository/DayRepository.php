<?php

namespace Trolley\AgendaBundle\Repository;

use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\Month;
use Trolley\AgendaBundle\Util\MonthOverview;

/**
 * DayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DayRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Holt aus der DB die Passende Tagen
     *
     * @param array $Days
     *
     * @return array
     */
    public function findDaysByDate(array $Days)
    {
        $qB = $this->createQueryBuilder('d');

        $qB->where(
            $qB->expr()->in('DATE_FORMAT(d.taDay, \'%Y-%m-%d\')', $Days)
        );

        return $qB->getQuery()->getResult();
    }

    /**
     * Alle Tage des Monats
     *
     * @param array $Days of \Trolley\AgendaBundle\Entity\Day Objects
     *
     * @return array
     */
    public function findDaysByMonth(MonthOverview $monthOverview)
    {
        $months = array_map(
            function($month) {
                return $month->getDate()->format("Y-m");
            },
            $monthOverview->getArrayCopy()
        );

        $qB = $this->createQueryBuilder('d');

        $qB->where(
            $qB->expr()->in('DATE_FORMAT(d.taDay, \'%Y-%m\')', $months)
        );
        $qB->orderBy('d.taDay');

        return $qB->getQuery()->getResult();
    }
}
