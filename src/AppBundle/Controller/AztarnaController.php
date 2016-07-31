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
     * @Route("/", name="aztarna_index")
     * @Method("GET")
     */
    public function indexAction()
    {

//        $map = $this->get('fungio_google_map.map');
//        $marker = $this->get('fungio_google_map.marker');
//        $marker->setPrefixJavascriptVariable('marker_');
//        $marker->setPosition(43.341636,-1.786679, true);
//        $map->addMarker($marker);
//
//        $polyline = new Polyline();
//        $polyline->setOption('geodesic', true);
//        $polyline->setOption('strokeColor', '#FF0000');
//        $polyline->setOption('weight', 5);

        $em = $this->getDoctrine()->getManager();

        $aztarnas = $em->getRepository('AppBundle:Aztarna')->findAll();

//        $kont=0;
//        foreach ($aztarnas as $puntua)
//        {
//            $kont++;
//            $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
//        }

//        $map->addPolyline($polyline);
        return $this->render('aztarna/index.html.twig', array(
            'aztarnas' => $aztarnas,
//            'map' => $map
        ));

/*
        $map = $this->get('fungio_google_map.map');

        $marker = $this->get('fungio_google_map.marker');
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition(43.341636,-1.786679, true);
        $map->addMarker($marker);





        $em = $this->getDoctrine()->getManager();
        $aztarnas = $em->getRepository('AppBundle:Aztarna')->findAll();


        foreach ($aztarnas as $puntua)
        {
            $marker = $this->get('fungio_google_map.marker');
            $marker->setPosition($puntua->getLatitudea(),$puntua->getLongitudea(), true);
            $marker->setIcon('/puntua.png');
//            $marker->setOption('clickable', true);
//            $marker->setOption('flat', true);

            $map->addMarker($marker);
//            $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
        }

//        $map->addPolyline($polyline);
        return $this->render('aztarna/index.html.twig', array(
            'aztarnas' => $aztarnas,
            'map' => $map
        ));


*/












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
