<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Trolley\AgendaBundle\Util\FishClass;

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
        $this->_fillRestUser($pages);

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
        $this->_fillRestUser($pages);

        foreach ($pages as &$users) {
            $users = array_reverse($users);
        }

        return $this->render('TrolleyAgendaBundle:InfoCards:cover_cards.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * RestUser
     */
    protected function _fillRestUser(&$pages) {
        $lastElementIndex = count($pages)-1;
        $lastElement = $pages[$lastElementIndex];
        $fishClass = new FishClass();
        for ($icount = count($lastElement); $icount < 8; $icount++) {
            $lastElement[] = $fishClass;
        }
        $pages[$lastElementIndex] = $lastElement;
    }

}
