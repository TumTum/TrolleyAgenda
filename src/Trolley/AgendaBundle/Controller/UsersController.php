<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Security("has_role('ROLE_ADMIN')");
     */
    public function editAction(User $user, Request $request)
    {
        list ($isSaveDB, $form) = $this->_userFromSave($user, $request);

        if ($isSaveDB) {
            $this->addFlash('success', 'page.user.saved_sussfully');
            return $this->redirectToRoute('trolley_agenda_users_list');
        }

        return $this->render('TrolleyAgendaBundle:Users:edit.html.twig', [
            'user' => $user,
            'user_form' => $form->createView(),
        ]);
     }

    /**
     * @Route("/new");
     * @Security("has_role('ROLE_ADMIN')");
     *
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $user->setEnabled('true');
        
        list ($isSaveDB, $form) = $this->_userFromSave($user, $request);

        if ($isSaveDB) {
            $this->addFlash('success', 'page.user.saved_sussfully');
            return $this->redirectToRoute('trolley_agenda_users_edit', ['id' => $user->getId()]);
        }

        return $this->render('TrolleyAgendaBundle:Users:edit.html.twig', [
            'user' => $user,
            'user_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete")
     * @Security("has_role('ROLE_ADMIN')");
     * @Method({"POST"})
     *
     * @param Request $request
     */
    public function deleteAction(Request $request)
    {
        $userid     = $request->get('userid');
        $csrf_token = $request->get('_csrf_token');

        $RedirectResponse = $this->redirectToRoute('trolley_agenda_users_list');

        if (!$this->isCsrfTokenValid('delete_user', $csrf_token)) {
            $this->addFlash('danger', 'error.access_deny');

            return $RedirectResponse;
        }
        if (empty($userid)) {
            $this->addFlash('danger', 'page.user.error.unknown_user');
            return $RedirectResponse;
        }

        if ($this->getUser()->getId() == $userid) {
            $this->addFlash('danger', 'page.user.error.remove_self');
            return $RedirectResponse;
        }

        $userRepository = $this->getDoctrine()->getRepository('TrolleyAgendaBundle:User');
        $user = $userRepository->find($userid);

        if (empty($user)) {
            $this->addFlash('danger', 'page.user.error.unknown_user');
            return $RedirectResponse;
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'page.user.remove_sussfully');
        return $this->redirectToRoute('trolley_agenda_users_list');
    }

    /**
     * Speichert den User in der DB anhand des Formulars
     *
     * @param User    $user
     * @param Request $request
     *
     * @return array [ boolean is gespeichert in der DB,
     *                 from Object für die anzeige ]
     */
    protected function _userFromSave(User $user, Request $request)
    {
        $form = $this->createForm('Trolley\\AgendaBundle\\Form\\UserType', $user);

        $form->handleRequest($request);
        $saved = false;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            try {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $saved = true;
            } catch (\Exception $e) {
                $this->addFlash('danger', 'page.user.error.user_edit_save_faild');
                $saved = false;
            }
        }

        return [$saved, $form];
    }
}
