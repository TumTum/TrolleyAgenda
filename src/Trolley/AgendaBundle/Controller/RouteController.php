<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RouteController extends Controller
{
    /**
     * @Route("/route")
     */
    public function routeAction()
    {
        return $this->render('TrolleyAgendaBundle:Route:route.html.twig', []);
    }

}
