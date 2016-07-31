<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Ibilgailua;
use AppBundle\Form\IbilgailuaType;

use Fungio\GoogleMap\Map;
use Fungio\GoogleMap\MapTypeId;
use Fungio\GoogleMap\Overlays\Polyline;

use Fungio\GoogleMap\Events\MouseEvent;
use Fungio\GoogleMap\Overlays\InfoWindow;

use AppBundle\Entity\Aztarna;

/**
 * Ibilgailua controller.
 *
 * @Route("/ibilgailua/{_locale}")
 *         requirements={
 *           "_locale": "eu|es",
 */
class IbilgailuaController extends Controller
{
    /**
     * Lists all Ibilgailua entities.
     *
     * @Route("/", name="ibilgailua_index")
     *     }
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ibilgailuas = $em->getRepository('AppBundle:Ibilgailua')->findAll();

        $query = $em->createQuery('
            SELECT DISTINCT a.eguna FROM AppBundle:Aztarna a 
            ORDER BY a.eguna ASC
          ');
//        $query->setParameter('ibilgailua', $ibilgailua);
        $egunak = $query->getResult();

        $gaur=date('Y-m-d');


        return $this->render('ibilgailua/index.html.twig', array(
            'ibilgailuas' => $ibilgailuas,
            'egunak' => $egunak,
            'gaur'=>$gaur
        ));
    }

    /**
     * Creates a new Ibilgailua entity.
     *
     * @Route("/new", name="ibilgailua_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ibilgailua = new Ibilgailua();
        $form = $this->createForm('AppBundle\Form\IbilgailuaType', $ibilgailua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ibilgailua);
            $em->flush();

            return $this->redirectToRoute('ibilgailua_show', array('id' => $ibilgailua->getId()));
        }

        return $this->render('ibilgailua/new.html.twig', array(
            'ibilgailua' => $ibilgailua,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ibilgailua entity.
     *
     * @Route("/{id}/edit", name="ibilgailua_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ibilgailua $ibilgailua)
    {
        $deleteForm = $this->createDeleteForm($ibilgailua);
        $editForm = $this->createForm('AppBundle\Form\IbilgailuaType', $ibilgailua);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ibilgailua);
            $em->flush();

            return $this->redirectToRoute('ibilgailua_edit', array('id' => $ibilgailua->getId()));
        }

        return $this->render('ibilgailua/edit.html.twig', array(
            'ibilgailua' => $ibilgailua,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Ibilgailua entity.
     *
     * @Route("/{id}/{eguna}", name="ibilgailua_show")
     * @Method("GET")
     */
    public function showAction(Ibilgailua $ibilgailua,$eguna)
    {
//        $eguna='2014/07/28';
        $map = $this->get('fungio_google_map.map');
        $this->markerra($map,$ibilgailua->getLongitudea(),$ibilgailua->getLatitudea(),'/Home.png','<p>Home</p>');

        $em = $this->getDoctrine()->getManager();
//        $query = $em->createQuery('
//            SELECT a FROM AppBundle:Aztarna a
//              WHERE a.ibilgailua = :ibilgailua AND a.eguna = :eguna
//          ');
        $query = $em->createQuery('
            SELECT a.eguna,a.ordua,a.longitudea,a.latitudea,a.abiadura,a.inklinazioa FROM AppBundle:Aztarna a 
              WHERE a.ibilgailua = :ibilgailua AND a.eguna=:eguna
              ORDER BY a.eguna ASC,a.ordua ASC
          ');

        $query->setParameter('ibilgailua', $ibilgailua);
        $query->setParameter('eguna', $eguna);

//        $query = $em->createQuery('
//            SELECT a FROM AppBundle:Aztarna a
//              WHERE a.ibilgailua = :ibilgailua AND a.eguna LIKE :eguna
//          ');
//        $query->setParameter('ibilgailua', $ibilgailua);
//        $query->setParameter('eguna', $eguna.'%');


        $aztarnas = $query->getResult();
        $kont=0;
        $puntua=array();
        foreach ($aztarnas as $puntua)
        {
            if ($kont==0)
            {
//                $edukia = '<b>long: </b>' . $puntua->getLongitudea() . "<br/>";
//                $edukia = $edukia . '<b>lat: </b>' . $puntua->getLatitudea() . "<br/>";
//                $edukia = $edukia . '<b>spd: </b>' . $puntua->getAbiadura() . "<br/>";
//                $edukia = $edukia . '<b>Inklinazioa: </b>' . $puntua->getInklinazioa() . "<br/>";
//                $edukia = $edukia . '<b>Ordua: </b>' . $puntua->getOrdua()->format("H:i:s") . "<br/>";
//                $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntuaHasi2.png',$edukia);

                $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
                $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
                $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
                $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
                $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
                $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
                $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntuaHasi2.png',$edukia);




            }
            if ($kont == 15) {

//                $marker = $this->get('fungio_google_map.marker');
//                $marker->setPosition($puntua->getLatitudea(), $puntua->getLongitudea(), true);
//                $marker->setIcon('/puntua.png');
//                $marker->setOption('clickable', true);
//                $marker->setOption('flat', true);
//
//                $infoWindow = new InfoWindow();
//                $infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');

//                $edukia = '<b>long: </b>' . $puntua->getLongitudea() . "<br/>";
//                $edukia = $edukia . '<b>lat: </b>' . $puntua->getLatitudea() . "<br/>";
//                $edukia = $edukia . '<b>spd: </b>' . $puntua->getAbiadura() . "<br/>";
//                $edukia = $edukia . '<b>Inklinazioa: </b>' . $puntua->getInklinazioa() . "<br/>";
//                $edukia = $edukia . '<b>Ordua: </b>' . $puntua->getOrdua()->format("H:i:s") . "<br/>";
//                $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntua.png',$edukia);

                $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
                $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
                $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
                $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
                $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
                $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
                $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntua.png',$edukia);


//                $infoWindow->setContent($edukia);
//
//                $infoWindow->setOpen(false);
//                $infoWindow->setAutoOpen(true);
//                $infoWindow->setOpenEvent(MouseEvent::CLICK);
//                $infoWindow->setAutoClose(false);
//                $infoWindow->setOption('disableAutoPan', true);
//                $infoWindow->setOption('zIndex', 10);
//                $infoWindow->setOptions(array(
//                    'disableAutoPan' => true,
//                    'zIndex' => 10,
//                ));
//                $marker->setInfoWindow($infoWindow);
//                $map->addMarker($marker);
                $kont=1;
            }
            $kont=$kont+1;
        }
//        $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
//        $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
//        $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
//        $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
//        $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
//        $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
//        $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntuaAmaitu2.png',$edukia);

        if ($puntua)
        {
            $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
            $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
            $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
            $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
            $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
            $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
            $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntuaAmaitu2.png',$edukia);
        }

        $query = $em->createQuery('
            SELECT DISTINCT a.eguna FROM AppBundle:Aztarna a 
            WHERE a.ibilgailua = :ibilgailua
            ORDER BY a.eguna ASC
          ');
        $query->setParameter('ibilgailua', $ibilgailua);
        $egunak = $query->getResult();


//        $deleteForm = $this->createDeleteForm($ibilgailua);
//        $map->addPolyline($polyline);
        return $this->render('ibilgailua/show.html.twig', array(
            'ibilgailua' => $ibilgailua,
            'map' => $map,
            'egunak'=>$egunak
        ));
    }

//    /**
//     * Finds and displays a Ibilgailua entity.
//     *
//     * @Route("/{id}/track/{eguna}", name="ibilgailua_track")
//     * @Method("GET")
//     */
//    public function trackAction(Ibilgailua $ibilgailua,$eguna)
//    {
////        $eguna='2014/07/28';
//        $map = $this->get('fungio_google_map.map');
//
//        $this->markerra($map,$ibilgailua->getLongitudea(),$ibilgailua->getLatitudea(),'/Home.png','<p>Home</p>');
//
//
//        $em = $this->getDoctrine()->getManager();
//        $query = $em->createQuery('
//            SELECT a FROM AppBundle:Aztarna a
//              WHERE a.ibilgailua = :ibilgailua AND a.eguna LIKE :eguna
//              ORDER BY a.eguna ASC,a.ordua ASC
//          ');
//        $query->setParameter('ibilgailua', $ibilgailua);
//        $query->setParameter('eguna', $eguna.'%');
//        $aztarnas = $query->getResult();
//
//        $polyline = new Polyline();
////        $polyline->setOption('geodesic', true);
////        $polyline->setOption('strokeColor', '#FF0000');
//        $polyline->setOption('strokeColor', $ibilgailua->getRutakolorea());
//        $polyline->setOption('weight', 5);
//
////dump($aztarnas);
//        dump(count ($aztarnas, 0));
//        $kop=(int)(count($aztarnas, 0)/2200);
//        dump($kop);
////        dump ((int)(7/2));
//
//        $kont=0;
//        $berria=1;
//        $azkenPuntua = new Aztarna();
//        foreach ($aztarnas as $puntua)
//        {
//            $kont = $kont + 1;
//            if (($azkenPuntua->getEguna()==$puntua->getEguna()) || (!$azkenPuntua->getEguna()))
//            {
//                if ($berria==1)
//                {
//                    $edukia='<b>long: </b>'.$puntua->getLongitudea()."<br/>";
//                    $edukia=$edukia.'<b>lat: </b>'.$puntua->getLatitudea()."<br/>";
//                    $edukia=$edukia.'<b>spd: </b>'.$puntua->getAbiadura()."<br/>";
//                    $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua->getInklinazioa()."<br/>";
//                    $edukia=$edukia.'<b>Eguna: </b>'.$puntua->getEguna()->format("Y/m/d")."<br/>";
//                    $edukia=$edukia.'<b>Ordua: </b>'.$puntua->getOrdua()->format("H:i:s")."<br/>";
//                    $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntuaHasi2.png',$edukia);
//
//                    $berria=0;
//
//                }
////                if ($kont == 25) {
//                if ($kont == $kop) {
//                    $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
////                $map->addPolyline($polyline);
//                    $kont = 0;
//                }
//            }
//            else
//            {
//                $map->addPolyline($polyline);
//
//                $edukia='<b>long: </b>'.$azkenPuntua->getLongitudea()."<br/>";
//                $edukia=$edukia.'<b>lat: </b>'.$azkenPuntua->getLatitudea()."<br/>";
//                $edukia=$edukia.'<b>spd: </b>'.$azkenPuntua->getAbiadura()."<br/>";
//                $edukia=$edukia.'<b>Inklinazioa: </b>'.$azkenPuntua->getInklinazioa()."<br/>";
//                $edukia=$edukia.'<b>Eguna: </b>'.$azkenPuntua->getEguna()->format("Y/m/d")."<br/>";
//                $edukia=$edukia.'<b>Ordua: </b>'.$azkenPuntua->getOrdua()->format("H:i:s")."<br/>";
//                $this->markerra($map,$azkenPuntua->getLongitudea(),$azkenPuntua->getLatitudea(),'/puntuaAmaitu2.png',$edukia);
//
//                $polyline=new Polyline();
////                $polyline->setOption('strokeColor', '#FF0000');
//                $polyline->setOption('strokeColor','#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6));
//                $polyline->setOption('weight', 5);
//                $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
//                $kont = 0;
//                $berria=1;
//            }
//            $azkenPuntua=$puntua;
//        }
//        if ($puntua)
//        {
//            $map->addPolyline($polyline);
//
//            $edukia = '<b>long: </b>' . $puntua->getLongitudea() . "<br/>";
//            $edukia = $edukia . '<b>lat: </b>' . $puntua->getLatitudea() . "<br/>";
//            $edukia = $edukia . '<b>spd: </b>' . $puntua->getAbiadura() . "<br/>";
//            $edukia = $edukia . '<b>Inklinazioa: </b>' . $puntua->getInklinazioa() . "<br/>";
//            $edukia = $edukia . '<b>Eguna: </b>' . $puntua->getEguna()->format("Y/m/d") . "<br/>";
//            $edukia = $edukia . '<b>Ordua: </b>' . $puntua->getOrdua()->format("H:i:s") . "<br/>";
//
//            $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntuaAmaitu2.png',$edukia);
//        }
//
//
////        $map->addPolyline($polyline);
//        return $this->render('ibilgailua/show.html.twig', array(
//            'ibilgailua' => $ibilgailua,
//            'map' => $map
//        ));
//    }

    /**
     * Finds and displays a Ibilgailua entity.
     *
     * @Route("/{id}/track/{eguna}", name="ibilgailua_track")
     * @Method("GET")
     */
    public function trackAction(Ibilgailua $ibilgailua,$eguna)
    {
//        $eguna='2014/07/28';
        $map = $this->get('fungio_google_map.map');
        $this->markerra($map,$ibilgailua->getLongitudea(),$ibilgailua->getLatitudea(),'/Home.png','<p>Home</p>');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
            SELECT a.eguna FROM AppBundle:Aztarna a 
              WHERE a.ibilgailua = :ibilgailua AND a.eguna LIKE :eguna
              GROUP BY a.eguna
          ');
        $query->setParameter('ibilgailua', $ibilgailua);
        $query->setParameter('eguna', $eguna.'%');
        $egunak = $query->getResult();
//        dump ($egunak);
        foreach ($egunak as $egun)
        {
            $query = $em->createQuery('
            SELECT a.eguna,a.ordua,a.longitudea,a.latitudea,a.abiadura,a.inklinazioa FROM AppBundle:Aztarna a 
              WHERE a.ibilgailua = :ibilgailua AND a.eguna=:eguna
              ORDER BY a.eguna ASC,a.ordua ASC
          ');
            $query->setParameter('ibilgailua', $ibilgailua);
            $query->setParameter('eguna', $egun['eguna']);
//            $query->setParameter('eguna', '2014-07-27%');

            $aztarnas = $query->getResult();
            $polyline = new Polyline();
            $polyline->setOption('strokeColor', $ibilgailua->getRutakolorea());
//            $polyline->setOption('strokeColor','#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6));
            $polyline->setOption('strokeColor','#' . substr(str_shuffle('ABCDEF0123456789'), 0, 2).'0000');

            $polyline->setOption('weight', 5);
            $kop=(int)(count($aztarnas, 0)/2200);
            $kont=0;
            foreach ($aztarnas as $puntua)
            {
//                if ($kont==0)
//                {
//                    $edukia='<b>long: </b>'.$puntua->getLongitudea()."<br/>";
//                    $edukia=$edukia.'<b>lat: </b>'.$puntua->getLatitudea()."<br/>";
//                    $edukia=$edukia.'<b>spd: </b>'.$puntua->getAbiadura()."<br/>";
//                    $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua->getInklinazioa()."<br/>";
//                    $edukia=$edukia.'<b>Eguna: </b>'.$puntua->getEguna()->format("Y/m/d")."<br/>";
//                    $edukia=$edukia.'<b>Ordua: </b>'.$puntua->getOrdua()->format("H:i:s")."<br/>";
//                    $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntuaHasi2.png',$edukia);
//
//                    $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
//                }
//                if ($kont == $kop) {
//                    $polyline->addCoordinate($puntua->getLatitudea(), $puntua->getLongitudea(), true);
//                    $kont = 1;
//                }
//                $kont=$kont+1;
                if ($kont==0)
                {
                    $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
                    $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
                    $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
                    $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
                    $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
                    $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
                    $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntuaHasi2.png',$edukia);

                    $polyline->addCoordinate($puntua['latitudea'], $puntua['longitudea'], true);
                }
                if ($kont == $kop) {
                    $polyline->addCoordinate($puntua['latitudea'], $puntua['longitudea'], true);
                    $kont = 1;
                }
                $kont=$kont+1;
            }
            $map->addPolyline($polyline);

//            $edukia='<b>long: </b>'.$puntua->getLongitudea()."<br/>";
//            $edukia=$edukia.'<b>lat: </b>'.$puntua->getLatitudea()."<br/>";
//            $edukia=$edukia.'<b>spd: </b>'.$puntua->getAbiadura()."<br/>";
//            $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua->getInklinazioa()."<br/>";
//            $edukia=$edukia.'<b>Eguna: </b>'.$puntua->getEguna()->format("Y/m/d")."<br/>";
//            $edukia=$edukia.'<b>Ordua: </b>'.$puntua->getOrdua()->format("H:i:s")."<br/>";
//            $this->markerra($map,$puntua->getLongitudea(),$puntua->getLatitudea(),'/puntuaAmaitu2.png',$edukia);
            $edukia='<b>long: </b>'.$puntua['longitudea']."<br/>";
            $edukia=$edukia.'<b>lat: </b>'.$puntua['latitudea']."<br/>";
            $edukia=$edukia.'<b>spd: </b>'.$puntua['abiadura']."<br/>";
            $edukia=$edukia.'<b>Inklinazioa: </b>'.$puntua['inklinazioa']."<br/>";
            $edukia=$edukia.'<b>Eguna: </b>'.$puntua['eguna']->format("Y/m/d")."<br/>";
            $edukia=$edukia.'<b>Ordua: </b>'.$puntua['ordua']->format("H:i:s")."<br/>";
            $this->markerra($map,$puntua['longitudea'],$puntua['latitudea'],'/puntuaAmaitu2.png',$edukia);

        }
        $query = $em->createQuery('
            SELECT DISTINCT a.eguna FROM AppBundle:Aztarna a 
            WHERE a.ibilgailua = :ibilgailua
            ORDER BY a.eguna ASC
          ');
        $query->setParameter('ibilgailua', $ibilgailua);
        $egunak = $query->getResult();

        return $this->render('ibilgailua/show.html.twig', array(
            'ibilgailua' => $ibilgailua,
            'map' => $map,
            'egunak'=>$egunak
        ));
    }



    private function markerra($mapa,$longitudea,$latitudea,$ikonoa,$edukia )
    {
        $marker = $this->get('fungio_google_map.marker');
        $marker->setPosition($latitudea, $longitudea, true);
        $marker->setIcon($ikonoa);
        $marker->setOption('clickable', true);
        $marker->setOption('flat', true);

        $infoWindow = new InfoWindow();
        $infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');

        $infoWindow->setContent($edukia);

        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent(MouseEvent::CLICK);
        $infoWindow->setAutoClose(false);
        $infoWindow->setOption('disableAutoPan', true);
        $infoWindow->setOption('zIndex', 10);
        $infoWindow->setOptions(array(
            'disableAutoPan' => true,
            'zIndex' => 10,
        ));
        $marker->setInfoWindow($infoWindow);
        $mapa->addMarker($marker);
    }


    /**
     * Deletes a Ibilgailua entity.
     *
     * @Route("/{id}", name="ibilgailua_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ibilgailua $ibilgailua)
    {
        $form = $this->createDeleteForm($ibilgailua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ibilgailua);
            $em->flush();
        }

        return $this->redirectToRoute('ibilgailua_index');
    }

    /**
     * Creates a form to delete a Ibilgailua entity.
     *
     * @param Ibilgailua $ibilgailua The Ibilgailua entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ibilgailua $ibilgailua)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ibilgailua_delete', array('id' => $ibilgailua->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
