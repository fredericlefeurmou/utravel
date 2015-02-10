<?php

namespace UTravel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

use UTravel\OpinionBundle\Entity\University;

class AdminController extends Controller
{
    /*
     *  ACCUEIL : Vue d'accueil de l'interface administateur
     */
    public function indexAction()
    {
        return $this->render(
            'UTravelAdminBundle:AdminInterface:index.html.twig',
            array(
                'title' => 'Interface administrateur',
                'id_service' => '-1',
            )
        );
    }


    /*
     *  AVIS : Page de gestion des avis : consulter, masquer et supprimer
     */
    public function gererAvisAction()
    {
        return $this->render(
            'UTravelAdminBundle:AdminInterface:gestionavis.html.twig',
            array(
                'title' => 'Gestion des avis des étudiants',
                'id_service' => '0'
            )
        );
    }

    /*
     *  Fonction pour uplaoder simplement des images dans le dossier /web/uploads
     *  - /actus pour les illustrations d'actualité
     *  - /carrousel pour les images du carrousel de la page d'accueil
     */
    protected function uploadImage($image, $dossier)
    {
        $repertoire_img = __DIR__.'/../../../../web/uploads/'.$dossier;
        $nom_img = $image->getClientOriginalName();

        // Vérifier les extensions
        $deb_ext = strrpos($nom_img, '.');
        $extension = substr($nom_img, $deb_ext + 1);
        $extensions_imgs = array(
            'bmp', 'gif', 'jpg', 'jpeg', 'png', 'svg',
            'BMP', 'GIF', 'JPG', 'JPEG', 'PNG', 'SVG'
        );
        if (!in_array($extension, $extensions_imgs))
        {
            throw new \UnexpectedValueException(
                "Extension invalide (".$extension."). Votre image doit etre de type : bmp, gif, jpg, jpeg, png, svg");
        }

        // Filtrer les caractères spéciaux pour etre réutilisé correctement
        $accents = array(
            'à','á','â','ã','ä','å',
            'ç','è','é','ê','ë',
            'ì','í','î','ï',
            'ð','ò','ó','ô','õ','ö',
            'ù','ú','û','ü','ý','ÿ');
        $sans_accents = array(
            'a','a','a','a','a','a',
            'c','e','e','e','e',
            'i','i','i','i',
            'o','o','o','o','o','o',
            'u','u','u','u','y','y');
        $nom_img = str_replace($accents, $sans_accents, $nom_img);

        $image->move($repertoire_img, $nom_img);
        return $nom_img;
    }
}