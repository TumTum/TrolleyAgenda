<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Util\MonthOverview;

/**
 * Class CalendarController
 *
 * @package Trolley\AgendaBundle\Controller
 *
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
    /**
     * @Route("/");
     */
    public function indexAction()
    {
        $Template = 'TrolleyAgendaBundle:Calendar:userView.html.twig';

        if ($this->isGranted('ROLE_ADMIN')) {
            $Template = 'TrolleyAgendaBundle:Calendar:adminView.html.twig';
        }

        $months = $this->_createAheadMonths();

        return $this->render($Template, [
            'months'     => $months,
            'controller' => $this,
        ]);
    }

    /**
     * @Method("GET")
     * @Route("/addme/{day}")
     */
    public function addUserToDayAction(Request $request, Day $day)
    {
           $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'error.no_user_found');
        }
        $day->addUser($user);

        $manager =$this->getDoctrine()->getManager();
        $manager->persist($day);
        $manager->flush();

        $this->addFlash('info', 'page.calendar.user_successful_added');

        return $this->redirectToRoute('trolley_agenda_calendar_index');
    }

    /**
     * Meldest den benutzer ab vom Tag
     *
     * @Route("/signoffme/{day}")
     *
     * @param Request $request
     * @param Day     $day
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function signoffUserFromDayAction(Request $request, Day $day)
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'error.no_user_found');
        }
        $day->removeUser($user);

        $manager =$this->getDoctrine()->getManager();
        $manager->persist($day);
        $manager->flush();

        $this->addFlash('info', 'page.calendar.user_successful_signoff');

        return $this->redirectToRoute('trolley_agenda_calendar_index');
    }

    /**
     * @Route("/accept-{user}-on-{day}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAcceptUser(User $user, Day $day)
    {
        $day->userAcceptToGo($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($day);
        $manager->flush();

        $this->addFlash('success', 'page.calendar.user_successful_accept');
        return $this->redirectToRoute('trolley_agenda_calendar_index');
    }

    /**
     * @Route("/signoff-{user}-on-{day}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminSignOffUser(User $user, Day $day)
    {
        $day->removeUser($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($day);
        $manager->flush();

        $this->addFlash('success', 'page.calendar.user_successful_signoff');
        return $this->redirectToRoute('trolley_agenda_calendar_index');
    }

    /**
     * Alle Usernamen zurück vom Vornamen
     *
     * @return array
     */
    public function getListOfUserFirstname()
    {
        $userRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User');
        return $userRepository->findAutocompleteFirstlastname('');
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
        $MonthOverview->mergeDaysWithDB();

        return $MonthOverview;
    }
}
