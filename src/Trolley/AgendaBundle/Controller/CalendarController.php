<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Util\MonthOverview;

class CalendarController extends Controller
{
    /**
     * @Route("/", name="startpage");
     */
    public function indexAction()
    {
        $day = new Day("06.02.2016");
        $day2 = new Day("13.02.2016");
        $day3 = new Day("20.02.2016");
        $months['Februar'][] = $day;
        $months['Februar'][] = $day2;
        $months['Februar'][] = $day3;
        $months['Februar'][] = new Day("27.02.2016");
        $months['März'][] = new Day("05.03.2016");
        $months['März'][] = new Day("12.03.2016");
        $months['März'][] = new Day("19.03.2016");
        $months['März'][] = new Day("26.03.2016");


        $day->setTaUsers(
            [
                [ 'name' => "Tobias Matthaiou", 'isAccept' => false ],
                [ 'name' => "Bruder Franz", 'isAccept' => true ],
            ]
        );

        $day2->setTaUsers(
            [
                [ 'name' => "Tabitha Matthaiou", 'isAccept' => true ],
                [ 'name' => "Tobias Matthaiou", 'isAccept' => true ],
            ]
        );

        $day3->setTaUsers(
            [
                [ 'name' => "Heidi vom Berg", 'isAccept' => false ],
            ]
        );

        $Template = 'TrolleyAgendaBundle:Calendar:userView.html.twig';

        if ($this->isGranted('ROLE_ADMIN')) {
            $Template = 'TrolleyAgendaBundle:Calendar:adminView.html.twig';
        }

        $months = $this->_createAheadMonths();

        return $this->render($Template, [
            'months' => $months
        ]);
    }

    /**
     * Rechnet die Monate zurück
     */
    protected function _createAheadMonths()
    {
        $looking_months = $this->getParameter('trolley_agenda.calendar.month_looking_ahead');
        $dayname = $this->getParameter('trolley_agenda.calendar.every_day');

        $MonthOverview = new MonthOverview();
        $MonthOverview->createAheadMonth($looking_months);
        $MonthOverview->fillMonthWithDaysFor($dayname);

        return $MonthOverview;
    }

}
