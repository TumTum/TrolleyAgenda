<?php

namespace Trolly\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CalendarController extends Controller
{
    /**
     * @Route("/", name="startpage")
     */
    public function indexAction()
    {
        return $this->render('TrollyAgendaBundle:Calendar:index.html.twig', array(
            // ...
        ));
    }

}
