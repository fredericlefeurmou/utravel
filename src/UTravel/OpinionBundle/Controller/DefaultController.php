<?php

namespace UTravel\OpinionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UTravel\AdminBundle\Entity\WelcomeWidget;

class DefaultController extends Controller {

    public function indexAction() {
        $widgets = $this->getDoctrine()->getManager()->getRepository('UTravelAdminBundle:WelcomeWidget')->findAll();

        $images = array();
        $path = __DIR__ . '/../../../../web/uploads/carrousel';
        if ($dossier_imgs = opendir($path)) {
            while (($fichier = readdir($dossier_imgs)) !== false) {
                if ($fichier != '.' && $fichier != '..') {
                    $images[] = $fichier;
                }
            }
        }

        $params = array();
        if ($widgets != null) {
            $params = array(
                        'is_video' => $widgets[0]->getIsVideo(),
                        'video_link' => $widgets[0]->getIframeLink(),
                        'images' => $images
            );
        } else {
            $params = array(
                        'is_video' => false,
                        'video_link' => null,
                        'images' => $images
            );
        }
        
        return $this->render('UTravelOpinionBundle:Default:index.html.twig', $params);
    }

}
