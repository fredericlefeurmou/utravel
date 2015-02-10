<?php

namespace UTravel\UTravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use UTravel\AdminBundle\Entity\Pays;

/*
 *    InfosController : controleur de toute la section "Informations" du site
 *    disponible auprès du public : Actualités, Fiches pays, Universités
 */
class InfosController extends Controller
{
    /*
     *  Action retournant la page d'accueil des fiches pays disponibles
     */
    public function fichesPaysAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $fiches = $manager->getRepository('UTravelAdminBundle:Pays')->findAll();
        return $this->render(
            'UTravelUTravelBundle:Default:fiches_pays_public.html.twig',
            array('title' => 'Fiches pays', 'fiches' => $fiches)
        );
    }

    /*
     *  Action retournant le template de la fiche pays (méthode GET) :
     *  - si argument 'pdf' existe, retoune un pdf généré dynamiquement
     *  - sinon, retourne une page HTML avec une mise en forme similaire
     */
    public function consultFicheAction($pays)
    {
        $manager = $this->getDoctrine()->getManager();
        $fiche = $manager->getRepository('UTravelAdminBundle:Pays')->findByNom($pays);

        if ($fiche != null)
        {
            $values = $this->get('fiche_pays_controller')->generateFicheValues($fiche[0], $manager);
            if ($this->getRequest()->query->get('pdf') == "true")
            {
                $html = $this->renderView('UTravelUTravelBundle:Default:template_pdf.html.twig', array(
                    'values'  => $values
                ));

                return new Response(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                    200,
                    array(
                        'Content-Type'          => 'application/pdf',
                        'Content-Disposition'   => 'filename="fiche_pays.pdf"'
                    )
                );
            }
            else
            {
                return $this->render('UTravelUTravelBundle:Default:fiche_pays_display.html.twig', array(
                    'values' => $values
                ));
            }
        }
        else
        { $success = false; }
    }

    /*
     *    Action affichant le fil d'actualités pour une section (ensemble de 5 actus) donnée
     */
    public function actualitesAction($section)
    {
        if ($section == null) { $section = 1; }
        $em = $this->getDoctrine()->getManager();
        $tab_actus = $em->getRepository('UTravelAdminBundle:Actualite');

        // id de l'actualite la plus recente
        $max_id = $tab_actus->getMaxId();
        // nb de pages ou les actus sont reparties
        $nb_parts = ceil(count($tab_actus->findAll()) / 5);

        if ($section > 1)
        {
            // 1. Filtrer les actualités des sections précédentes
            $nb_actus = ($section - 1) * 5;
            $recentes_actus = $tab_actus->findBy(
                array(), array('dateCreation' => 'desc'), $nb_actus, null);
            $min_id = $recentes_actus[count($recentes_actus) - 1]->getId();

            // 2. Récupérer les actus du lot suivant, seuls les 5 seront affichés par le template twig
            $actus = $tab_actus->findActusFrom($min_id);
        }
        else
        {
            $actus = $tab_actus->findActusFrom($max_id + 1);
        }

        return $this->render('UTravelUTravelBundle:Default:actualites_public.html.twig', array(
            'title' => 'Actualités UTravel',
            'actus' => $actus,
            'num_page' => $section,
            'nb_pages' => $nb_parts,
        ));
    }

    /*
     *   Retourne une page simple avec uniquement le contenu complet d'une seule actualité
     */
    public function consultActuAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $actualite = $em->getRepository('UTravelAdminBundle:Actualite')->find($id);

        return $this->render('UTravelUTravelBundle:Default:actualite_display.html.twig', array(
            'title' => 'Actualités UTravel',
            'actu' => $actualite
        ));
    }

    /*
     *  Retourne la page d'accueil des universités partenaires de l'UTC
     *  avec pour chacune d'entre elles un lien vers le site de l'université
     */
    public function consultUniversitesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $universites = $em->getRepository('UTravelOpinionBundle:University')->displayUniversities();

        return $this->render('UTravelUTravelBundle:Default:universites_public.html.twig', array(
            'title' => 'Universités partenaires de l\'UTC',
            'universites' => $universites
        ));
    }


    /*
     *  Formulaire de depot de remarques sur le site UTravel en version beta.
     *  Action d'affichage
     */
    public function feedbackFormAction()
    {
        return $this->render('UTravelUTravelBundle:Default:feedback.html.twig', array());
    }

    /*
     *  Formulaire de depot de remarques sur le site UTravel en version beta.
     *  Action d'envoi du message poste
     */
    public function sendFeedbackAction()
    {
        $expediteur = "Jean-Claude";
        $corps_message = "Salut";
        
        // 1ere option : Bundle SwiftMailer

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('dcoqueux@gmail.com')
            ->setTo('dcoqueux@gmail.com')
            ->setBody(
                $this->renderView('UTravelUTravelBundle:Default:feedback.html.twig', array(
                    'expediteur' => $expediteur,
                    'corps_message' => $corps_message
                ))
            );

        $this->get('mailer')->send($message);

        // 2eme option : fonction mail PHP

        // mail("dcoqueux@gmail.com", "Notification feedback", $corps_message)

        return $this->render('UTravelUTravelBundle:Default:feedback_sent.html.twig', array());
    }
}
