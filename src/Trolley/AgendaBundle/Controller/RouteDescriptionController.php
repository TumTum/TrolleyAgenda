<?php

namespace Trolley\AgendaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Trolley\AgendaBundle\Entity\RouteDescription;
use Trolley\AgendaBundle\Form\RouteDescriptionType;

/**
 * RouteDescription controller.
 *
 * @Security("has_role('ROLE_ADMIN')");
 * @Route("/route-description")
 */
class RouteDescriptionController extends Controller
{
    /**
     * Lists all RouteDescription entities.
     *
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $routeDescriptions = $em->getRepository('TrolleyAgendaBundle:RouteDescription')->findAll();

        return $this->render('TrolleyAgendaBundle:RouteDescription:index.html.twig', array(
            'routeDescriptions' => $routeDescriptions,
        ));
    }

    /**
     * Creates a new RouteDescription entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $routeDescription = new RouteDescription();
        $form = $this->createForm('Trolley\AgendaBundle\Form\RouteDescriptionType', $routeDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($routeDescription);
            $em->flush();

            return $this->redirectToRoute('trolley_agenda_routedescription_show', array('id' => $routeDescription->getId()));
        }

        return $this->render('TrolleyAgendaBundle:RouteDescription:new.html.twig', array(
            'routeDescription' => $routeDescription,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a RouteDescription entity.
     *
     * @Route("/{id}.html")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')");
     */
    public function showAction(RouteDescription $routeDescription)
    {
        $deleteForm = $this->createDeleteForm($routeDescription);

        return $this->render('TrolleyAgendaBundle:RouteDescription:show.html.twig', array(
            'routeDescription' => $routeDescription,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RouteDescription entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RouteDescription $routeDescription)
    {
        $deleteForm = $this->createDeleteForm($routeDescription);
        $editForm = $this->createForm('Trolley\AgendaBundle\Form\RouteDescriptionType', $routeDescription);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($routeDescription);
            $em->flush();

            return $this->redirectToRoute('trolley_agenda_routedescription_edit', array('id' => $routeDescription->getId()));
        }

        return $this->render('TrolleyAgendaBundle:RouteDescription:edit.html.twig', array(
            'routeDescription' => $routeDescription,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a RouteDescription entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RouteDescription $routeDescription)
    {
        $form = $this->createDeleteForm($routeDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($routeDescription);
            $em->flush();
        }

        return $this->redirectToRoute('trolley_agenda_routedescription_index');
    }

    /**
     * Creates a form to delete a RouteDescription entity.
     *
     * @param RouteDescription $routeDescription The RouteDescription entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RouteDescription $routeDescription)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trolley_agenda_routedescription_delete', array('id' => $routeDescription->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
