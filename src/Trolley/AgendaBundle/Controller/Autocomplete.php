<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 16.04.16
 * Time: 17:45
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Repository\UserRepository;

/**
 * Class AutocompleUser
 *
 * @Route("/autocomplete")
 * @package Trolley\AgendaBundle\Controller
 */
class Autocomplete extends Controller
{
    /**
     * @Route("/name_search")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchUsernameAction(Request $request)
    {
        $q = $request->query->get('q');
        $q = trim($q);

        $results = [];

        if (!empty($q)) {
            /** @var UserRepository $userRepository */
            $userRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User');
            $results = $userRepository->findAutocompleteFirstlastname($q);
        }

        return new JsonResponse($results);
    }
}