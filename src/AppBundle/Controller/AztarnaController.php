<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Aztarna;
use AppBundle\Form\AztarnaType;
use Fungio\GoogleMap\Map;
use Fungio\GoogleMap\MapTypeId;
use Fungio\GoogleMap\Overlays\Polyline;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Aztarna controller.
 *
 * @Route("/aztarna")
 */
class AztarnaController extends Controller
{
    /**
     * Lists all Aztarna entities.
     *
     * @Route("/", defaults={"page" = 1}, name="aztarna_index")
     * @Route("/page{page}", name="aztarna_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $gaur=date('Y-m-d');

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
            SELECT a.eguna,a.ordua,a.longitudea,a.latitudea,a.abiadura,a.inklinazioa FROM AppBundle:Aztarna a 
              WHERE a.eguna=:eguna
              ORDER BY a.eguna ASC,a.ordua ASC
          ');
        $query->setParameter('eguna', $gaur);
        $aztarnas = $query->getResult();

//        $aztarnas = $em->getRepository('AppBundle:Aztarna')->findAll();

        $adapter = new ArrayAdapter($aztarnas);
        $pagerfanta = new Pagerfanta($adapter);


        try {
            $entities = $pagerfanta
                // Le nombre maximum d'éléments par page
                ->setMaxPerPage('25')
                // Notre position actuelle (numéro de page)
                ->setCurrentPage($page)
                // On récupère nos entités via Pagerfanta,
                // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                ->getCurrentPageResults()
            ;
        } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }


        return $this->render('aztarna/index.html.twig', array(
//            'aztarnas' => $aztarnas,
            'aztarnas' => $entities,
            'pager' => $pagerfanta,
        ));

    }

    /**
     * Creates a new Aztarna entity.
     *
     * @Route("/new", name="aztarna_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $aztarna = new Aztarna();
        $form = $this->createForm('AppBundle\Form\AztarnaType', $aztarna);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($aztarna);
            $em->flush();

            return $this->redirectToRoute('aztarna_show', array('id' => $aztarna->getId()));
        }

        return $this->render('aztarna/new.html.twig', array(
            'aztarna' => $aztarna,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Aztarna entity.
     *
     * @Route("/{id}", name="aztarna_show")
     * @Method("GET")
     */
    public function showAction(Aztarna $aztarna)
    {
        $deleteForm = $this->createDeleteForm($aztarna);

        return $this->render('aztarna/show.html.twig', array(
            'aztarna' => $aztarna,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Aztarna entity.
     *
     * @Route("/{id}/edit", name="aztarna_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Aztarna $aztarna)
    {
        $deleteForm = $this->createDeleteForm($aztarna);
        $editForm = $this->createForm('AppBundle\Form\AztarnaType', $aztarna);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($aztarna);
            $em->flush();

            return $this->redirectToRoute('aztarna_edit', array('id' => $aztarna->getId()));
        }

        return $this->render('aztarna/edit.html.twig', array(
            'aztarna' => $aztarna,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Aztarna entity.
     *
     * @Route("/{id}", name="aztarna_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Aztarna $aztarna)
    {
        $form = $this->createDeleteForm($aztarna);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($aztarna);
            $em->flush();
        }

        return $this->redirectToRoute('aztarna_index');
    }

    /**
     * Creates a form to delete a Aztarna entity.
     *
     * @param Aztarna $aztarna The Aztarna entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Aztarna $aztarna)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aztarna_delete', array('id' => $aztarna->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
