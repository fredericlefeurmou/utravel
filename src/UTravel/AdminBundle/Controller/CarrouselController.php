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

class CarrouselController extends AdminController
{
    /*
     *   CARROUSEL : Page de gestion du carrousel en page d'accueil
     *   Action appelée pour la méthode GET (accueil)
     *   et POST (dépot d'images ou changement lien iframe)
     */
    public function carrouselFormAction()
    {
        $requete = $this->getRequest();
        // Widget de la page d'accueil : enregistrement unique
        $em = $this->getDoctrine()->getManager();
        $widgets = $em->getRepository('UTravelAdminBundle:WelcomeWidget')->findAll();
        $widget = ($widgets == null) ? new WelcomeWidget() : $widgets[0];

        try {
            if ($requete->getMethod() == 'POST')
            {               
                $message = "La configuration pour la page d'accueil a bien été mise à jour. ";
                if ($requete->request->get('option') == 'carrousel')
                {
                    $widget->setIsVideo(false);
                    if ($requete->files->get('illustration') != null)
                    {
                        $this->uploadImage($requete->files->get('illustration'), 'carrousel/');
                    }
                    $message .= "Vous avez choisi l'option \"carrousel\".";
                }
                elseif ($requete->request->get('option') == 'video')
                {
                    $lien = $requete->request->get('embedded');
                    $widget->setIsVideo(true);
                    if (preg_match("@<iframe.*?>\s*</iframe>@", $lien))
                    {
                        $widget->setIframeLink($lien);
                    }
                    else { throw new \UnexpectedValueException("Le lien vers la vidéo est incorrect"); }
                    $message .= "Vous avez choisi l'option \"image statique avec vidéo intégrée\".";
                }

                $em->persist($widget);
                $em->flush();
            }
            else { $message = ''; }
        } catch (\UnexpectedValueException $e) {
            return $this->render(
                'UTravelAdminBundle:AdminInterface:validation.html.twig',
                array(
                    'title' => 'Mise à jour du carrousel',
                    'id_service' => '3',
                    'success' => false,
                    'err_msg' => $e->getMessage()
                )
            );
        }

        return $this->render(
            'UTravelAdminBundle:AdminInterface:carrousel.html.twig',
            array(
                'title' => 'Mise à jour du carrousel',
                'id_service' => '3',
                'iframe' => $widget->getIframeLink(),
                'message' => $message
            )
        );
    }

    /*
     *    CARROUSEL : Rafraichir la liste d'images du carroussel
     */
    public function manageCarrouselAction()
    {
        $reponse_text = '';
        $route = $this->container->get('request')->get('_route');
        if ($route == 'utravel_delete_carrousel')
        {
            $file_to_delete = $this->getRequest()->request->get('file');
        }
        else
        {
            $file_to_delete = null;
        }

        $images = $this->getCarrouselImages($file_to_delete);

        $html_reponse = new Response();
        foreach($images as $image)
        {
            $reponse_text .= '<div class="input-group">'
                .'<span class="input-group-btn">'
                    .'<button class="suppr-img btn btn-danger btn-sm" type="button" name="'.$image.'">'
                        .'<i class="fa fa-times"></i>'
                    .'</button>'
                .'</span>'
                .'<input type="text" class="form-control" value="'.$image.'" readonly>'
            .'</div>';
        }
        $html_reponse->setContent($reponse_text);
        return $html_reponse;
    }

    private function getCarrouselImages($file_to_delete)
    {
        $images = array();
        $path = __DIR__.'/../../../../web/uploads/carrousel';
        if ($dossier_imgs = opendir($path))
        {
            while(($fichier = readdir($dossier_imgs)) !== false)
            {
                if($fichier != '.' && $fichier != '..')
                {
                    if ($fichier == $file_to_delete)
                    {
                        unlink($path.'/'.$fichier);
                    }
                    else
                    {
                        $images[] = $fichier;
                    }
                }
            }
        }

        return $images;
    }

}