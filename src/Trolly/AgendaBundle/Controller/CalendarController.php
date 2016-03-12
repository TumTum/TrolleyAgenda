<?php

namespace Trolly\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CalendarController extends Controller
{
    /**
     * @Route("/", name="startpage");
     */
    public function indexAction()
    {
        return $this->render('TrollyAgendaBundle:Calendar:index.html.twig', array(
            'months' => [
                [
                    'name' => 'Februar',
                    'days' => [
                         [
                            "date"  => 'Samstag 06.02.2016',
                            "users" => ['Tobias', 'Jörg'],
                         ],
                         [
                            "date"  => 'Samstag 13.02.2016',
                            "users" => ['Tobias', 'Jörg'],
                         ],
                         [
                            "date"  => 'Samstag 29.02.2016',
                            "users" => ['Tobias', 'Jörg'],
                         ],
                         [
                            "date"  => 'Samstag 27.02.2016',
                            "users" => ['Tobias', 'Jörg'],
                         ],
                    ]
                ],
                [
                    'name' => 'März',
                    'days' => [

                        [
                            "date"  => 'Samstag 05.03.2016',
                            "users" => ['Tobias', 'Jörg'],
                        ],
                        [
                            "date"  => 'Samstag 12.03.2016',
                            "users" => ['Tobias', 'Jörg'],
                        ],
                        [
                            "date"  => 'Samstag 19.03.2016',
                            "users" => ['Tobias', 'Jörg'],
                        ],
                        [
                            "date"  => 'Samstag 26.03.2016',
                            "users" => ['Tobias', 'Jörg'],
                        ],
                    ]
                ]
            ]
        ));
    }

}
