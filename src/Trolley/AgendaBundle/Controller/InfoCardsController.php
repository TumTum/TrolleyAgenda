<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class InfoCardsController
 *
 * @package Trolley\AgendaBundle\Controller
 *
 * @Route("/print/cards")
 */
class InfoCardsController extends Controller
{
    /**
     * @Route("/")
     */
    public function allUserCardsAction()
    {
        $users = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User')->findAll();
        $host = $_SERVER['HTTP_HOST'];

        $pages = array_chunk($users, 8);

        return $this->render('TrolleyAgendaBundle:InfoCards:all_user_cards.html.twig', [
            'host' => $host,
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/cover")
     */
    public function coverCardsAction()
    {
        $users = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User')->findAll();

        $pages = array_chunk($users, 8);
        foreach ($pages as &$users) {
            $users = array_reverse($users);
        }

        return $this->render('TrolleyAgendaBundle:InfoCards:cover_cards.html.twig', [
            'pages' => $pages
        ]);
    }

}
