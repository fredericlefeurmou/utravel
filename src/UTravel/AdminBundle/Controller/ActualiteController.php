<?php

namespace UTravel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use UTravel\AdminBundle\Entity\Actualite;
use UTravel\AdminBundle\Entity\WelcomeWidget;

class ActualiteController extends AdminController
{
    /*
     *  ACTUALITE : Formulaire d'édition (création + mise à jour)
     *  ajouter un texte, un titre et uploader une illustration.
     */
    public function editActuAction($id = -1)
    {
        // Valeurs par défaut d'arguments Twig (création pure)
        $champs = array('image' => '', 'titre' => '', 'desc' => '');
        $titre = "Création d'une nouvelle actualité";

        $route = $this->container->get('request')->get('_route');
        if ($route == 'utravel_update_news')
        {

            $em = $this->getDoctrine()->getManager();
            $tab_actus = $em->getRepository('UTravelAdminBundle:Actualite');
            $actu = $tab_actus->find($id);

            if ($actu != null)
            {
                $champs = array(
                    'image' => $actu->getImagePath(),
                    'titre' => $actu->getTitre(),
                    'desc' => $actu->getDescription()
                );
                $titre = "Modification d'une actualité";
            }
            else { $id = -1; }
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:nouvelleactu.html.twig',
            array(
                'title' => $titre,
                'id_service' => '2',
                'id_actu' => $id,
                'champs' => $champs
            )
        );
    }

    /*
     *  ACTUALITE - VALIDATION : Page de création d'une actu :
     *  ajouter un texte, un titre et uploader une illustration
     */
    public function validActuAction()
    {
        $requete = $this->getRequest();
        $image = $requete->files->get('illustration');

        $em = $this->getDoctrine()->getManager();
        $tab_actus = $em->getRepository('UTravelAdminBundle:Actualite');
        $actualite = $tab_actus->find($requete->request->get('id_actu'));

        try {
            if ($requete->request->get('titre') == null || $requete->request->get('description') == null)
            {
                $success = false;
                $err_msg = "Le titre et/ou le contenu manque, l'actualité ne peut pas être créée.";
            }
            else
            {
                if ($actualite == null)
                {
                    // Nouvelle actualité
                    if ($image == null)
                    {
                        $success = false;
                        $err_msg = "Il faut une illustration : l'actualité ne peut pas être créée.";
                    }
                    else
                    {
                        $nom_img = $this->uploadImage($image, 'actus/');
                        $actualite = new Actualite(
                            $nom_img, $requete->request->get('titre'),  $requete->request->get('description'));
                        $em->persist($actualite);
                    }
                }
                else
                {
                    // Actualité existante : illustation optionnelle
                    $nom_img = $image == null ? null : $this->uploadImage($image, 'actus/');
                    $actualite->reset(
                        $nom_img, $requete->request->get('titre'), $requete->request->get('description'));
                    $em->persist($actualite);
                }
            }
            $em->flush();
        }
        catch (\UnexpectedValueException $e) {
            $success = false;
            $err_msg = $e->getMessage();
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:validation.html.twig',
            array(
                'title' => 'Validation de l\'actualité',
                'id_service' => '2',
                'success' => isset($success) ? $success : true,
                'err_msg' => isset($err_msg) ? $err_msg : ''
            )
        );
    }


    /*
     *  ACTUALITE - GESTION : Page de gestion des actus 
     *  Affiche un tableau d'actus (contenu complété par AJAX par d'autres actions)
     *  Recherche des actualités par date décroissante
     *  Possibilité de modifier et supprimer
     */
    public function gererActuAction($message = '')
    {
        $em = $this->getDoctrine()->getManager();
        $actus = $em->getRepository('UTravelAdminBundle:Actualite')->findBy(
            array(), array('dateCreation' => 'desc'), null, null);

        return $this->render(
            'UTravelAdminBundle:AdminInterface:gestionactus.html.twig',
            array(
                'title' => 'Gestion du fil d\'actualités du site',
                'id_service' => '2',
                'actus' => $actus,
                'message' => $message
            )
        );
    }

    public function removeActuAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $actu = $em->getRepository('UTravelAdminBundle:Actualite')->find($id);

        if ($actu != null)
        {
            $em->remove($actu);
            $em->flush();
            $message = "L'actualité intitulée \"".$actu->getTitre()."\" a bien été supprimée";
        }
        else
        { $message = "Aucune actualité n'a été supprimé en base de données"; }
        
        return $this->gererActuAction($message);
    }
}