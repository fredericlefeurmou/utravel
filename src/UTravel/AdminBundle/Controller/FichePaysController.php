<?php

namespace UTravel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use UTravel\AdminBundle\Entity\Pays;
use UTravel\AdminBundle\Entity\Ambassade;
use UTravel\AdminBundle\Entity\Annexe;
use UTravel\AdminBundle\Entity\Attache;
use UTravel\AdminBundle\Entity\Bureau;
use UTravel\AdminBundle\Entity\Campus;
use UTravel\AdminBundle\Entity\Consulat;
use UTravel\AdminBundle\Entity\ListeNumeros;
use UTravel\AdminBundle\Entity\Lycee;

use UTravel\AdminBundle\Entity\Actualite;
use UTravel\AdminBundle\Entity\WelcomeWidget;

class FichePaysController extends AdminController
{
    /*
     *   FICHE PAYS - GESTION : Page très simple de présentation des fiches
     *   existantes, et accès à leur modification
     */
    public function gererFichePaysAction()
    {
        $em = $this->getDoctrine()->getManager();
        $fiches = $em->getRepository('UTravelAdminBundle:Pays')->findAll();

        return $this->render(
            'UTravelAdminBundle:AdminInterface:gestionfiches.html.twig',
            array(
                'title' => 'Gestion des fiches pays',
                'id_service' => '1',
                'fiches' => $fiches
            )
        );
    }

    public function supprimerFicheAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('UTravelAdminBundle:Pays')->find($id);

        if ($fiche != null)
        {
            $many_entites = array('Annexe', 'Attache', 'Bureau', 'Consulat', 'Lycee');
            foreach ($many_entites as $entite)
            {
                $tab_entites = $em->getRepository('UTravelAdminBundle:'.$entite)->findByPays($fiche);
                foreach ($tab_entites as $obj) { $em->remove($obj); }
            }
            $em->flush();

            $one_entites = array(
                "liste_numeros" => $fiche->getListeNumeros(),
                'ambassade' => $fiche->getAmbassade(),
                'campus' => $fiche->getCampusFrance()
            );
            
            $em->remove($fiche);

            foreach ($one_entites as $obj) { if ($obj != null) { $em->remove($obj); } }
            $em->flush();
        }

        return $this->gererFichePaysAction();
    }

    /*
     *  FICHE PAYS : Formulaire d'édition (création + mise à jour)
     */
    public function fichePaysAction($id = -1)
    {
        $em = $this->getDoctrine()->getManager();
        $tab_fiches = $em->getRepository('UTravelAdminBundle:Pays');
        $fiche = $tab_fiches->find($id);
        if ($fiche == null) { $id = -1 ; }
        $values = $this->generateFicheValues($fiche, $em);

        $route = $this->container->get('request')->get('_route');
        $title = ($id == -1) ? "Création d'une fiche pays" : "Modification d'une fiche pays";

        return $this->render(
            'UTravelAdminBundle:AdminInterface:fichepays.html.twig',
            array(
                'title' => $title,
                'id_service' => '1',
                'id_fiche' => $id,
                'values' => $values
            )
        );
    }


    /*
     *    Génère un tableau de valeurs initiales pour le formulaire,
     *    - vide si création pure
     *    - avec les valeurs relatives à la fiche si modification
     */
    public function generateFicheValues($fiche, $manager)
    {
        if ($fiche == null)
        {
            $is_fiche = $is_numeros = $is_ambassade = $is_campus = false;
        }
        else
        {
            $is_fiche = true;
            $is_numeros = $fiche->getListeNumeros() == null ? false : true;
            $is_ambassade = $fiche->getAmbassade() == null ? false : true;
            $is_campus = $fiche->getCampusFrance() == null ? false : true;
            $tab_consulats = $manager->getRepository('UTravelAdminBundle:Consulat');
            $consulats = $tab_consulats->findByPays($fiche);
            $tab_attaches = $manager->getRepository('UTravelAdminBundle:Attache');
            $attaches = $tab_attaches->findByPays($fiche);
            $tab_lycees = $manager->getRepository('UTravelAdminBundle:Lycee');
            $lycees = $tab_lycees->findByPays($fiche);
            $tab_bureaux = $manager->getRepository('UTravelAdminBundle:Bureau');
            $bureaux = $tab_bureaux->findByPays($fiche);
            $tab_annexes = $manager->getRepository('UTravelAdminBundle:Annexe');
            $annexes = $tab_annexes->findByPays($fiche);
        }

        /* -------------- Valeurs principales -------------- */
        $values = array(
            'nom'       => $is_fiche ? $fiche->getNom() : '',
            'drapeau'   => $is_fiche ? $fiche->getDrapeau() : '',
            'capitale'  => $is_fiche ? $fiche->getCapitale() : '',
            'langue'    => $is_fiche ? $fiche->getLangue() : '',
            'area'      => $is_fiche ? $fiche->getSuperficie() : '',
            'pop'       => $is_fiche ? $fiche->getPopulation() : '',
            'chef_etat' => $is_fiche ? $fiche->getChefEtat() : '',
            'jour_nat'  => $is_fiche ? $fiche->getJourNational() : '',
            'monnaie'   => $is_fiche ? $fiche->getMonnaie() : '',
            'taux'      => $is_fiche ? $fiche->getTaux() : '',
            'numeros'   => array(
                'police'    => $is_numeros ? $fiche->getListeNumeros()->getPolice() : '',
                'urgences'  => $is_numeros ? $fiche->getListeNumeros()->getSamu() : '',
                'pompiers'  => $is_numeros ? $fiche->getListeNumeros()->getPompiers() : ''
            ),
            'ambassade' => array(
                'adr'       => $is_ambassade ? $fiche->getAmbassade()->getAdresse() : '',
                'cp'        => $is_ambassade ? $fiche->getAmbassade()->getCp() : '',
                'ville'     => $is_ambassade ? $fiche->getAmbassade()->getVille() : '',
                'tel'       => $is_ambassade ? $fiche->getAmbassade()->getTelephone() : '',
                'url'       => $is_ambassade ? $fiche->getAmbassade()->getUrl() : ''
            ),
            'campus'    => array(
                'adr'       => $is_campus ? $fiche->getCampusFrance()->getAdresse() : '',
                'cp'        => $is_campus ? $fiche->getCampusFrance()->getCp() : '',
                'ville'     => $is_campus ? $fiche->getCampusFrance()->getVille() : '',
                'email'     => $is_campus ? $fiche->getCampusFrance()->getEmail() : ''
            ),
            'ubi_dir'   => $is_fiche ? $fiche->getUbifranceDir() : '',
            'chcom_dir' => $is_fiche ? $fiche->getCommerceDir() : ''
        );

        /* -------------- Consulats -------------- */
        if (isset($consulats) && $consulats != null)
        {
            foreach($consulats as $consulat)
            {
                $values['consulats'][] = array(
                    'ville' => $consulat->getVille(),
                    'adr'   => $consulat->getAdresse(),
                    'cp'    => $consulat->getCp(),
                    'tel'   => $consulat->getTelephone()
                );
            }
        }
        else { $values['consulats'] = array(); }
        
        /* -------------- Attachés scientifiques -------------- */
        if (isset($attaches) && $attaches != null)
        {
            foreach($attaches as $attache)
            {
                $values['attaches'][] = array(
                    'nom'   => $attache->getNom(),
                    'ville' => $attache->getVille(),
                    'email' => $attache->getEmail()
                );
            }
        }
        else { $values['attaches'] = array(); }

        /* -------------- Lycées français -------------- */
        if (isset($lycees) && $lycees != null)
        {
            foreach($lycees as $lycee)
            {
                $values['lycees'][] = array(
                    'nom'       => $lycee->getNom(),
                    'proviseur' => $lycee->getProviseur(),
                    'ville'     => $lycee->getVille(),
                    'adr'       => $lycee->getAdresse(),
                    'cp'        => $lycee->getCp(),
                    'tel'       => $lycee->getTelephone(),
                    'email'     => $lycee->getEmail()
                );
            }
        }
        else {  $values['lycees'] = array(); }

        /* -------- Bureaux nationaux : Ubifrance et Chambre du commerce -------- */
        $values['ubifrance'] = $values['chcom'] = array(
            'ville' => '', 'adr' => '', 'cp' => '', 'tel' => '', 'email' => ''
        );
        if (isset($bureaux) && $bureaux != null)
        {
            foreach($bureaux as $bureau)
            {
                $cle = $bureau->getIsUbifrance() ? 'ubifrance' : 'chcom';
                $values[$cle] = array(
                    'ville' => $bureau->getVille(),
                    'adr'   => $bureau->getAdresse(),
                    'cp'    => $bureau->getCp(),
                    'tel'   => $bureau->getTelephone(),
                    'email' => $bureau->getEmail()
                );
            }
        }

        /* -------- Annexes : Ubifrance et Chambre du commerce -------- */
        $values['ubi_ann'] = $values['chcom_ann'] = array();
        if (isset($annexes) && $annexes != null)
        {

            foreach($annexes as $annexe)
            {
                $cle = $annexe->getIsUbifrance() ? 'ubi_ann' : 'chcom_ann';
                $values[$cle][] = array(
                    'ville'     => $annexe->getVille(),
                    'adr'       => $annexe->getAdresse(),
                    'tel'       => $annexe->getTelephone(),
                    'cp'        => $annexe->getCp(),
                    'email'     => $annexe->getEmail()
                );
            }
        }

        return $values;
    }





    /*
     *   FICHE PAYS - VALIDATION : Action déclenchée par POST pour valider,
     *   et ajouter ou mettre à jour une fiche pays.
     */
    public function validFichePaysAction()
    {
        $requete = $this->getRequest();
        $champs = $requete->request;

        try {
            if ($champs->get('nom') == '') { throw new \UnexpectedValueException("Pas de pays choisi"); }
            
            $em = $this->getDoctrine()->getManager();
            $tab_fiches = $em->getRepository('UTravelAdminBundle:Pays');
            $fiches = $tab_fiches->findByNom($champs->get('nom'));

            /* ============================== CREATION DE LA FICHE PAYS ============================== */
            if ($fiches == null)
            {
                /*
                 * Entités devant être déclarées pour l'entité fiche pays
                 */

                // ---------------------------------------------------------- Numéros utiles

                if ($champs->get('num_police') != null
                    || $champs->get('num_samu') != null || $champs->get('num_pompiers') != null)
                {
                    $liste_numeros = new ListeNumeros($champs);
                }
                else { $liste_numeros = null; }

                // ---------------------------------------------------------- Ambassade

                if ($champs->get('amb_ville') != null)
                {
                    $ambassade = new Ambassade($champs);
                }
                else { $ambassade = null; }

                // ---------------------------------------------------------- Campus France

                if ($champs->get('campus_ville') != null)
                {
                    $campus_france = new Campus($champs);
                }
                else { $campus_france = null; }

                // ---------------------------------------------------------- Fiche pays

                $fiche = new Pays($champs, $liste_numeros, $ambassade, $campus_france);
                $drapeau = $requete->files->get('drapeau');
                if ($drapeau != null)
                {
                    $nom_img = $this->uploadImage($drapeau, 'drapeaux/');
                    $fiche->setDrapeau($nom_img);
                }

                // Sauvegarde Pays, et en cascade ListeNumeros, Ambassade et Campus France
                $em->persist($fiche); $em->flush();

                // ---------------------------------------------------------- Ubifrance : bureau national

                if ($champs->get('ubifrance_ville') != '')
                {
                    $ubifrance = new Bureau($fiche, true, $champs);
                    $em->persist($ubifrance); $em->flush();
                }

                // ---------------------------------------------------------- Chambre du commerce : bureau national

                if ($champs->get('chcom_ville') != '')
                {
                    $chambre = new Bureau($fiche, false, $champs);
                    $em->persist($chambre); $em->flush();
                }

                /*
                 *    Entités multiples (nécessitant la fiche pays comme paramètre constructeur)
                 */

                // ---------------------------------------------------------- Consulats

                $cpt = 1; $consulat = $champs->get("consul_".$cpt);
                while ($consulat !== null)
                {
                    $consulat_entity = new Consulat($fiche, $consulat);
                    // Sauvegarde et recherche d'une entité supplémentaire
                    $em->persist($consulat_entity); $em->flush();
                    $cpt++; $consulat = $champs->get("consul_".$cpt);
                }

                // ---------------------------------------------------------- Attachés scientifiques

                $cpt = 1; $attache = $champs->get("attache_".$cpt);
                while ($attache !== null)
                {
                    $attache_entity = new Attache($fiche, $attache);
                    // Sauvegarde et recherche d'une entité supplémentaire
                    $em->persist($attache_entity); $em->flush();
                    $cpt++; $attache = $champs->get("attache_".$cpt);
                }

                // ---------------------------------------------------------- Lycées français

                $cpt = 1; $lycee = $champs->get("lycee_".$cpt);
                while ($lycee !== null)
                {
                    $lycee_entity = new Lycee($fiche, $lycee);
                    // Sauvegarde et recherche d'une entité supplémentaire
                    $em->persist($lycee_entity); $em->flush();
                    $cpt++; $lycee = $champs->get("lycee_".$cpt);
                }

                // ---------------------------------------------------------- UbiFrance : annexes

                $cpt = 1; $ubifrance = $champs->get('ubi_'.$cpt);
                while ($ubifrance !== null)
                {
                    $ubifrance_entity = new Annexe($fiche, true, $ubifrance);
                    // Sauvegarde et recherche d'une entité supplémentaire
                    $em->persist($ubifrance_entity); $em->flush();
                    $cpt++; $ubifrance = $champs->get('ubi_'.$cpt);
                }

                // ---------------------------------------------------------- Chambres du commerce : annexes

                $cpt = 1; $chambre_annexe = $champs->get('chcom_ann_'.$cpt);
                while ($chambre_annexe !== null)
                {
                    $chambre_annexe_entity = new Annexe($fiche, false, $chambre_annexe);
                    // Sauvegarde et recherche d'une entité supplémentaire
                    $em->persist($chambre_annexe_entity); $em->flush();
                    $cpt++; $chambre_annexe = $champs->get('chcom_ann_'.$cpt);
                }
            }
            else
            /* ============================== MISE A JOUR DE LA FICHE PAYS ============================== */
            {
                $fiche_pays = $fiches[0];

                // ---------------------------------------------------------- Numéros utiles

                if ($fiche_pays->getListeNumeros() != null)
                {
                    if ($champs->get('num_police') == null
                        && $champs->get('num_samu') == null && $champs->get('num_pompiers') == null)
                    {
                        $liste_numeros = $em->getRepository('UTravelAdminBundle:ListeNumeros')
                                            ->find($fiche_pays->getListeNumeros()->getId());
                        $fiche_pays->setListeNumeros(null);
                        $em->remove($liste_numeros);
                    }
                    else {
                        $fiche_pays->getListeNumeros()->reset($champs);
                    }
                }
                else if ($champs->get('num_police') != null
                    || $champs->get('num_samu') != null || $champs->get('num_pompiers') != null)
                {
                    $fiche_pays->setListeNumeros(new ListeNumeros($champs));
                }

                // ---------------------------------------------------------- Ambassade

                if ($fiche_pays->getAmbassade() != null)
                {
                    if ($champs->get('amb_ville') == null) {
                        $ambassade = $em->getRepository('UTravelAdminBundle:Ambassade')
                                        ->find($fiche_pays->getAmbassade()->getId());
                        $fiche_pays->setAmbassade(null);
                        $em->remove($ambassade);
                    }
                    else {
                        $fiche_pays->getAmbassade()->reset($champs);
                    }
                }
                else if ($champs->get('amb_ville') != null)
                {
                    $fiche_pays->setAmbassade(new Ambassade($champs));
                }

                // ---------------------------------------------------------- Campus France

                if ($fiche_pays->getCampusFrance() != null)
                {
                    if ($champs->get('campus_ville') == null) {
                        $campus = $em->getRepository('UTravelAdminBundle:Campus')
                                     ->find($fiche_pays->getCampusFrance()->getId());
                        $fiche_pays->setCampusFrance(null);
                        $em->remove($campus);
                    }
                    else {
                        $fiche_pays->getCampusFrance()->reset($champs);
                    }
                }
                else if ($champs->get('campus_ville') != null)
                {
                    $fiche_pays->setCampusFrance(new Campus($champs)); 
                }

                // ---------------------------------------------------------- Fiche pays

                $fiche_pays->reset($champs);
                $drapeau = $requete->files->get('drapeau');
                if ($drapeau != null)
                {
                    $nom_img = $this->uploadImage($drapeau, 'drapeaux/');
                    $fiche_pays->setDrapeau($nom_img);
                }

                $em->persist($fiche_pays);
                $em->flush();

                // ---------------------------------------------------------- Ubifrance : bureau national

                $ubi_nat = $em->getRepository('UTravelAdminBundle:Bureau')
                              ->findOneBy(array('pays' => $fiche_pays, 'isUbifrance' => true));

                if ($ubi_nat != null)
                {
                    $ubi_nat->reset($champs);
                    $em->persist($ubi_nat);
                }
                else if ($champs->get('ubifrance_ville') != '')
                {
                    $ubi_nat = new Bureau($fiche_pays, true, $champs);
                    $em->persist($ubi_nat);
                }
                $em->flush();

                // ---------------------------------------------------------- Chambre du commerce : bureau national

                $chambre_nat = $em->getRepository('UTravelAdminBundle:Bureau')
                              ->findOneBy(array('pays' => $fiche_pays, 'isUbifrance' => false));

                if ($chambre_nat != null)
                {
                    $chambre_nat->reset($champs);
                    $em->persist($chambre_nat);
                }
                else if ($champs->get('chcom_ville') != '')
                {
                    $chambre_nat = new Bureau($fiche_pays, false, $champs);
                    $em->persist($chambre_nat);
                }
                $em->flush();

                /*
                 *    Entités multiples (nécessitant la fiche pays comme paramètre constructeur)
                 */

                // ---------------------------------------------------------- Consulats

                $tab_consulats = $em->getRepository('UTravelAdminBundle:Consulat');
                $consulats = $tab_consulats->findByPays($fiche_pays);

                if ($consulats != null) {
                    foreach ($consulats as $consulat) { $em->remove($consulat); } $em->flush();
                }

                $cpt = 1; $consulat = $champs->get("consul_".$cpt);
                while ($consulat !== null)
                {
                    $consulat_entity = new Consulat($fiche_pays, $consulat);
                    $em->persist($consulat_entity); $em->flush();
                    $cpt++; $consulat = $champs->get("consul_".$cpt);
                }

                // ---------------------------------------------------------- Attachés scientifiques

                $tab_attaches = $em->getRepository('UTravelAdminBundle:Attache');
                $attaches = $tab_attaches->findByPays($fiche_pays);
                if ($attaches != null) { foreach ($attaches as $attache) { $em->remove($attache); } $em->flush(); }

                $cpt = 1; $attache = $champs->get("attache_".$cpt);
                while ($attache !== null)
                {
                    $attache_entity = new Attache($fiche_pays, $attache);
                    $em->persist($attache_entity); $em->flush();
                    $cpt++; $attache = $champs->get("attache_".$cpt);
                }

                // ---------------------------------------------------------- Lycées français

                $tab_lycees = $em->getRepository('UTravelAdminBundle:Lycee');
                $lycees = $tab_lycees->findByPays($fiche_pays);
                if ($lycees != null) { foreach ($lycees as $lycee) { $em->remove($lycee); } $em->flush(); }
                $cpt = 1; $lycee = $champs->get("lycee_".$cpt);
                while ($lycee !== null)
                {
                    $lycee_entity = new Lycee($fiche_pays, $lycee);
                    $em->persist($lycee_entity); $em->flush();
                    $cpt++; $lycee = $champs->get("lycee_".$cpt);
                }

                $tab_annexes = $em->getRepository('UTravelAdminBundle:Annexe');

                // ---------------------------------------------------------- Annexes Ubifrance :
                // on supprime les anciennes et on cree de nouvelles entites

                $ubi_annexes = $tab_annexes->findBy(array('pays' => $fiche_pays, 'isUbifrance' => true));

                if ($ubi_annexes != null) {
                    foreach ($ubi_annexes as $ubi_annexe) { $em->remove($ubi_annexe); } $em->flush();
                }

                $cpt = 1; $ubi_annexe = $champs->get("ubi_".$cpt);
                while ($ubi_annexe !== null)
                {
                    $ubi_annexe_entity = new Annexe($fiche_pays, true, $ubi_annexe);
                    $em->persist($ubi_annexe_entity); $em->flush();
                    $cpt++; $ubi_annexe = $champs->get("ubi_".$cpt);
                }

                // ---------------------------------------------------------- Annexes Chambres du commerce :
                // on supprime les anciennes et on cree de nouvelles entites

                $chcom_annexes = $tab_annexes->findBy(array('pays' => $fiche_pays, 'isUbifrance' => false));

                if ($chcom_annexes != null) {
                    foreach ($chcom_annexes as $chcom_annexe) { $em->remove($chcom_annexe); } $em->flush();
                }

                $cpt = 1; $chcom_annexe = $champs->get("chcom_ann_".$cpt);
                while ($chcom_annexe !== null)
                {
                    $chcom_annexe_entity = new Annexe($fiche_pays, false, $chcom_annexe);
                    $em->persist($chcom_annexe_entity); $em->flush();
                    $cpt++; $chcom_annexe = $champs->get("chcom_ann_".$cpt);
                }
            }

            $em->flush();
            $success = true;
        } catch (\UnexpectedValueException $e) {
            $success = false;
            $err_msg = $e->getMessage();
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:validation.html.twig',
            array(
                'title' => 'Enregistrement de la fiche pays',
                'id_service' => '1',
                'success' => $success,
                'err_msg' => isset($err_msg) ? $err_msg : ''
            )
        );
    }
}