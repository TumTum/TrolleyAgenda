<?php

namespace Trolly\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction()
    {
        return $this->render('TrollyAgendaBundle:Users:list.html.twig', array(
            // ...
        ));
    }

}
