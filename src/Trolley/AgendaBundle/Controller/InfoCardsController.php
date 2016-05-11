<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class InfoCardsController
 *
 * @package Trolley\AgendaBundle\Controller
 *
 * @Route("/users")
 */
class InfoCardsController extends Controller
{
    /**
     * @Route("/all-cards")
     */
    public function allUserCardsAction()
    {
        $users = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User')->findAll();
        $host = $_SERVER['HTTP_HOST'];
        return $this->render('TrolleyAgendaBundle:InfoCards:all_user_cards.html.twig', [
            'host' => $host,
            'users' => $users
        ]);
    }

}
