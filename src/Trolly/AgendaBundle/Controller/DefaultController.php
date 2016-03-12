<?php

namespace Trolly\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('TrollyAgendaBundle:Default:index.html.twig');
    }

    /**
     * @Route("/homepage")
     */
    public function homepageAction()
    {
        return $this->render('TrollyAgendaBundle:Default:index.html.twig');
    }
}
