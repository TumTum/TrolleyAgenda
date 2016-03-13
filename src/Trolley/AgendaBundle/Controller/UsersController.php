<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Trolley\AgendaBundle\Entity\User;

/**
 * @Security("has_role('ROLE_USER')");
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/");
     * @Route("/list");
     */
    public function listAction()
    {
        $db = $this->getDoctrine()->getManager();
        $userDB = $db->getRepository("TrolleyAgendaBundle:User");

        return $this->render('TrolleyAgendaBundle:Users:list.html.twig',[
            'users' => $userDB->findAll()
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm('Trolley\\AgendaBundle\\Form\\UserType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('trolley_agenda_users_list');
        }

        return $this->render('TrolleyAgendaBundle:Users:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $form->createView(),
        ));
     }


}
