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
