<?php

namespace UTravel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use UTravel\OpinionBundle\Entity\University;

class UniversiteController extends AdminController
{
    /*
     *  UNIVERSITES - GESTION : Affiche le template de gestion des universités
     *  Le tableau avec les universités existant en BDD est dynamiquement mis à jour avec Ajax
     */
    public function gererUniversitesAction($message = '')
    {
        $em = $this->getDoctrine()->getManager();
        $universites = $em->getRepository('UTravelOpinionBundle:University')->findAll();

        return $this->render(
            'UTravelAdminBundle:AdminInterface:gestionunivs.html.twig',
            array(
                'title' => 'Gestion des universités',
                'id_service' => '4',
                'universites' => $universites,
                'message' => $message
            )
        );
    }

    /*
     *   UNIVERSITES - PARTENARIAT : Depuis la page de gestion,
     *   on change le partenariat de l'université. Retour sur la page de gestion après modification   
     */
    public function changePartnershipAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $universite = $em->getRepository('UTravelOpinionBundle:University')->find($id);

        if ($universite != null)
        {
            if ($universite->getPartnership())
                { $universite->setPartnership(false); }
            else
                { $universite->setPartnership(true); }
            $em->flush();

            $message = "Modification du partenariat réussi pour ".$universite->getName();
        }
        else
        {
            $message = "Pas de partenariat modifié car l'université n'existe pas en base de données";
        }

        return $this->gererUniversitesAction($message);
    }

    /*
     *   UNIVERSITES - PARTENARIAT : Depuis la page de gestion,
     *   on change le partenariat de l'université. Retour sur la page de gestion après modification   
     */
    public function suppressionUniversiteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $universite = $em->getRepository('UTravelOpinionBundle:University')->find($id);

        if ($universite != null)
        {
            $em->remove($universite);
            $em->flush();
            $message = "Suppression en base de données de l'université ".$universite->getName()." réussie";
        }
        else
        {
            $message = "Pas d'université modifié car l'université n'existe pas en base de données";
        }

        return $this->gererUniversitesAction($message);
    }

    /*
     *   UNIVERSITE - EDITION : Affiche le formulaire d'édition d'une université,
     *   vide si nouvelle université, rempli des valeurs existantes si appelé depuis la gestion
     */
    public function editUniversiteAction($id = -1)
    {
        $route = $this->container->get('request')->get('_route');
        $champs = array('nom' => '', 'ville' => '', 'pays' => '', 'lien' => '');

        if ($route == 'utravel_create_universities')
        {
            $titre = "Ajout d'une nouvelle université partenaire";
        }
        elseif ($route == 'utravel_update_universities')
        {
            $em = $this->getDoctrine()->getManager();
            $tab_univs = $em->getRepository('UTravelOpinionBundle:University');
            $universite = $tab_univs->find($id);

            if ($universite != null)
            {
                $titre = "Modification d'une université";
                $champs = array(
                    'nom'   => $universite->getName(),
                    'ville' => $universite->getCity(),
                    'pays'  => $universite->getCountry(),
                    'lien'  => $universite->getLink()
                );
            }
            else
            {  
                $titre = "Ajout d'une nouvelle université partenaire";
                $id = -1;
            }
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:nouvelleuniversite.html.twig',
            array(
                'title' => $titre,
                'id_service' => '4',
                'id_univ' => $id,
                'champs' => $champs
            )
        );
    }

    /*
     *   Action de controle des données transmises par le formulaire,
     *   et enregistrement en BDD si aucun problème.
     */
    public function validUniversiteAction()
    {
        $post = $this->getRequest()->request;
        $success = true;

        foreach (array('nom_univ', 'ville', 'nom_pays', 'url') as $champ)
        {
            if ($post->get($champ) == '') { $success = false; }
        }

        if ($success)
        {
            $em = $this->getDoctrine()->getManager();
            $tab_univs = $em->getRepository('UTravelOpinionBundle:University');
            $universite = $tab_univs->find($post->get('id_univ'));

            if ($universite == null)
            {
                // Nouvelle Universite : partenariat PASSIF pour être validé par la DRI
                $universite = new University(
                    $post->get('nom_univ'), $post->get('ville'), $post->get('nom_pays'), $post->get('url')
                );
            } 
            else
            {
                $universite->reset(
                    $post->get('nom_univ'), $post->get('ville'), $post->get('nom_pays'), $post->get('url')
                );
            }

            $em->persist($universite);
            $em->flush();
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:validation.html.twig',
            array(
                'title' => 'Validation de l\'université',
                'id_service' => '4',
                'success' => $success,
                'err_msg' => "L'un des champs n'a pas été rempli"
            )
        );
    }
}