<?php

namespace Trolly\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Trolly\AgendaBundle\Entity\User;

/**
 * @Security("has_role('ROLE_ADMIN')");
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/list");
     */
    public function listAction()
    {
        $db = $this->getDoctrine()->getManager();
        $userDB = $db->getRepository("TrollyAgendaBundle:User");

        return $this->render('TrollyAgendaBundle:Users:list.html.twig',[
            'users' => $userDB->findAll()
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm('Trolly\\AgendaBundle\\Form\\UserType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('trolly_agenda_users_edit', ['id' => $user->getId()]);
        }

        return $this->render('TrollyAgendaBundle:Users:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $form->createView(),
        ));
     }


}
