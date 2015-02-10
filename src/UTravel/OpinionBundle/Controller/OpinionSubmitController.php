<?php

namespace UTravel\OpinionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UTravel\OpinionBundle\Entity\OpinionStudy;
use UTravel\OpinionBundle\Entity\OpinionDiploma;
use UTravel\OpinionBundle\Entity\OpinionInternship;
use UTravel\OpinionBundle\Entity\OpinionPlacement;
use Symfony\Component\HttpFoundation\JsonResponse;
use UTravel\OpinionBundle\Entity\JourneyType;
use UTravel\OpinionBundle\Form\Type\CourseType;
use UTravel\OpinionBundle\Entity\User;
use UTravel\OpinionBundle\Entity\OpinionVote;

class OpinionSubmitController extends Controller
{
    public function newAction()
    {
        if (User::isAuthentificated($this->getRequest()->getSession())) {
            $journeyTypes = $this->getDoctrine()->getRepository('UTravelOpinionBundle:JourneyType')->findAll();
            return $this->render('UTravelOpinionBundle:OpinionSubmit:new.html.twig', array('journey_types' => $journeyTypes));
        } else {
            return $this->redirect($this->generateUrl('utravel_login', array('redirect_url' => 'nouveau_avis')));
        }
    }
    
    public function createAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        if (User::isAuthentificated($session)) {
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
            if ($user == null) { // Utilisateur pas encore ne base de donnée
                $user = new User($session);
                $manager->persist($user);
            }
            $journeyType = $request->request->getInt('journey_type');

            $opinion = null;
            switch ($journeyType) {
                case JourneyType::$Studying: $opinion = new OpinionStudy(); break;
                case JourneyType::$Diploma: $opinion = new OpinionDiploma(); break;
                case JourneyType::$Internship: $opinion = new OpinionInternship(); break;
                case JourneyType::$Placement: $opinion = new OpinionPlacement(); break;
                default: return new Response('Invalid request.', 400);
            }
            $opinion->setPublished(false);
            $opinion->setAuthor($user);
                    
            $manager->persist($opinion);
            $manager->flush();

            return $this->redirect($this->generateUrl('utravel_edit_opinion', array('opinion_id' => $opinion->getId())));
        } else {
            return $this->redirect($this->generateUrl('utravel_login'));
        }
    }
    
    private function createEditForm ($opinion, $formValidation=array()) {
        $sem =  $this->getLastYearSemesters(); 
        $form = $this->createFormBuilder($opinion, 
            array('validation_groups' => $formValidation))->getForm();
        
        $form->add('branch', 'entity', array(
                'class' => 'UTravelOpinionBundle:Branch',
                'label' => 'Branche : ',
                'required' => false,
                'empty_value' => 'Aucune',
                'empty_data' => null))
            ->add('specialization', 'entity', array(
                'class' => 'UTravelOpinionBundle:BranchSpecialization',
                'group_by' => 'branch',
                'label' => 'Filière : ',
                'required' => false,
                'empty_value' => 'Aucune',
                'empty_data' => null))
            ->add('fullSemester', 'choice', array(
                'choices' => array_combine($sem, $sem),
                'label' => 'Semestre : '))
            ->add('country', 'country', array(
                'label' => 'Pays : '))
            ->add('language', 'entity', array(
                'class' => 'UTravelOpinionBundle:MainLanguage',
                'label' => 'Langue : ',
                'required' => false,
                'empty_value' => 'Aucune'));
        
        // Partie logement
        $form->add('housingEnabled', 'checkbox', array())
             ->add('housingType', 'entity', array(
                'class' => 'UTravelOpinionBundle:HousingType',
                'label' => 'Type de logement : '))
            ->add('housingRent', 'money', array(
                'currency' => false,
                'label' => 'Loyer : '))
            ->add('noteHousing', 'integer', array(
                'precision' => 0,
                'label' => 'Satisfaction : '))
            ->add('housingComment', 'textarea', array());
        
        // Partie contact pour le logement
        $form->add('housingContactEnabled', 'checkbox', array())
            ->add('housingOrganization', 'text', array(
                'label' => 'Nom de l\'organisme : ',
                'max_length' => 100
            ))
            ->add('housingAdress1', 'text', array(
                'label' => 'Adresse : ',
                'max_length' => 255
            ))
            ->add('housingAdress2', 'text', array(
                'label' => 'Adressse 2 : ',
                'max_length' => 255
            ))
            ->add('housingPostalCode', 'text', array(
                'label' => 'Code postal : ',
                'max_length' => 20
            ))
            ->add('housingCity', 'text', array(
                'label' => 'Ville : ',
                'label_attr' => array('style' => 'width: 77px;'),
                'max_length' => 100
            ))
            ->add('housingCountry', 'country', array(
                'label' => 'Pays : '
            ))
            ->add('housingEmail', 'email', array(
                'label' => 'Email : ',
                'required' => false 
            ))
            ->add('housingPhone', 'text', array(
                'label' => 'Téléphone : ',
                'max_length' => 20,
                'required' => false 
            ));
        
        // Partie Vie étudiante
        $form->add('noteLife', 'integer', array(
                'precision' => 0,
                'label' => 'Satisfaction : '))
            ->add('lifeComment', 'textarea', array());
            
            
        // Partie Transport
        $form->add('transportEnabled', 'checkbox', array())
             ->add('transportType', 'entity', array(
                'class' => 'UTravelOpinionBundle:TransportType',
                'label' => 'Type de transport : '))
             ->add('transportCompany', 'text', array(
                'label' => 'Compagnie de transport : ',
                'label_attr' => array('class' => 'big'),
                'max_length' => 50))
             ->add('transportPrice', 'money', array(
                'currency' => false,
                'label' => 'Prix du voyage : '))
             ->add('transportComment', 'textarea', array())
             ->add('transportOnSite', 'textarea', array());
        
        
        // Partie Bilan
        $form->add('noteGeneral', 'integer', array(
                'precision' => 0,
                'label' => 'Satisfaction : '))
            ->add('generalComment', 'textarea', array())
            ->add('generalAdvice', 'textarea', array());
        
        // Partie Etude
        if ($opinion->journeyType == JourneyType::$Studying || 
                $opinion->journeyType == JourneyType::$Diploma) {
            $form->add('university', 'entity', array(
                'class' => 'UTravelOpinionBundle:University',
                'label' => 'Université : '))
                ->add('noteStudying', 'integer', array(
                'precision' => 0,
                'label' => 'Satisfaction : '));
            $form->add('courses', 'collection', array(
                'type' => new CourseType(),
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false,
                'prototype' => true, 
                'cascade_validation' => true));
            $form->add('studyingComment', 'textarea', array());
            
            if ($opinion->journeyType == JourneyType::$Diploma) {
                $form->add('diploma', 'text', array(
                    'label' => 'Diplôme préparé : ',
                    'label_attr' => array('class' => 'big'),
                    'max_length' => 100));
            }
        } else {
            $form->add('city', 'text', array(
                'label' => 'Ville : ',
                'max_length' => 100));
        }
        
        if ($opinion->journeyType == JourneyType::$Internship) {
            $form->add('companyName', 'text', array(
                'label' => 'Nom de l\'entreprise : ',
                'label_attr' => array('class' => 'big'),
                'max_length' => 100))
                ->add('companyLink', 'text', array(
                'label' => 'Site web de l\'entreprise : ',
                'label_attr' => array('class' => 'big'),
                'max_length' => 255 ))
                ->add('companyField', 'text', array(
                'label' => 'Domaine d\'activité de l\'entreprise : ',
                'label_attr' => array('class' => 'big'),
                'max_length' => 255 ))
                ->add('internshipTitle', 'text', array(
                'label' => 'Intitulé du stage : ',
                'label_attr' => array('class' => 'big'),
                'max_length' => 255 ))
                ->add('internshipDescription', 'textarea', array(
                'label' => 'Description du stage : ',
                'label_attr' => array('class' => 'big') ))
                ->add('noteInternship', 'integer', array(
                'precision' => 0,
                'label' => 'Satisfaction : '))
                ->add('internshipComment', 'textarea', array());
        }
        
        $form->add('published', 'checkbox')
             ->add('save', 'submit', array('label' => 'Enregistrer'))
             ->add('publish', 'submit', array('label' => 'Publier'));
        
        return $form;
    }
    
    public function editAction($opinion_id)
    {
        $session = $this->getRequest()->getSession();
        if (User::isAuthentificated($session)) {
            $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
            $opinion = $repository->find($opinion_id);
            if ($opinion == null) {
                throw $this->createNotFoundException();
            }
            $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
            if ($user != null && $opinion->getAuthor() != null && $user->getId() == $opinion->getAuthor()->getId()) {
                $form = $this->createEditForm($opinion)->createView();
                $options = array('opinion' => $opinion, 'semesters' => $this->getLastYearSemesters(), 'form' => $form);

                return $this->render('UTravelOpinionBundle:OpinionSubmit:edit.html.twig', $options);
            } else {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
        } else {
            return $this->redirect($this->generateUrl('utravel_login', array('redirect_url' => 'editer_avis')));
        }
    }
    
    private function isAuthor ($session, $opinion) {
        $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
        return ($user != null && $opinion->getAuthor() != null 
                && $user->getId() == $opinion->getAuthor()->getId());
    }
    
    public function deleteOpinionAction(Request $request) 
    {
        $req = $request->request;
        
        $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
        $opinion_id = $req->getInt('opinion_id');
        $opinion = $repository->find($opinion_id);
        
        // On controle que l'utilisateur est bien l'auteur de l'avis
        $session = $this->getRequest()->getSession();
        if (!User::isAuthentificated($session) || !$this->isAuthor($session, $opinion)) { 
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        } 
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($opinion);
        $manager->flush();  
        
        return new JsonResponse(array('status' => 'ok', 'opinion_id' => $opinion_id));
    } 
    
    public function voteOpinionAction(Request $request) 
    {
        $req = $request->request;
        
        $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
        $opinion_id = $req->getInt('opinion_id');
        $opinion = $repository->find($opinion_id);
        
        if ($opinion == null || !$opinion->getPublished()) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        
        // On controle que l'utilisateur est connecté
        $session = $this->getRequest()->getSession();
        if (!User::isAuthentificated($session)) { 
            return new JsonResponse(array('status' => 'not_connected', 'opinion_id' => $opinion_id));
        } 
        
        $user = new User($session);
        
        if ($opinion->getAuthor() && $opinion->getAuthor()->getLogin() === $user->getLogin()) {
            return new JsonResponse(array('status' => 'is_author', 'opinion_id' => $opinion_id));
        }
        
        $votes = $opinion->getVotes();
        foreach ($votes as $vote) {
            if ($vote->getLogin() == $user->getLogin()) {
                return new JsonResponse(array('status' => 'already_voted', 'opinion_id' => $opinion_id));
            }
        }
        
        $vote = new OpinionVote();
        $vote->setLogin($user->getLogin());
        $vote->setOpinion($opinion);
        
        $opinion->addVote($vote);
        
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($vote);
        $manager->persist($opinion);
        $manager->flush();  
        
        return new JsonResponse(array('status' => 'ok', 'opinion_id' => $opinion_id));
    }
    
    public function saveAction(Request $request) 
    {
        $req = $request->request;
        
        $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
        $opinion_id = $req->getInt('id_opinion');
        $opinion = $repository->find($opinion_id);
        
        // On controle que l'utilisateur est bien l'auteur de l'avis
        $session = $this->getRequest()->getSession();
        if (!User::isAuthentificated($session) || !$this->isAuthor($session, $opinion)) { 
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        } 
        
        $getForm = $req->get('form');
        $isPublish = isset($getForm['publish']);
        $housingEnabled = isset($getForm['housingEnabled']);
        $housingContactEnabled = isset($getForm['housingContactEnabled']);
        $transportEnabled = isset($getForm['transportEnabled']);
        $studyingEnabled = $opinion->journeyType == JourneyType::$Studying || $opinion->journeyType == JourneyType::$Diploma;
        $diplomaEnnabled = $opinion->journeyType == JourneyType::$Diploma;
        $intershipEnabled = $opinion->journeyType == JourneyType::$Internship;
        
        $validationGroups = array();
        if ($isPublish) {
            $validationGroups[] = 'general';
            $validationGroups[] = 'life';
            $validationGroups[] = 'conclusion';
            if ($studyingEnabled) { $validationGroups[] = 'studying'; }
            if (!$studyingEnabled) { $validationGroups[] = 'city'; }
            if ($diplomaEnnabled) { $validationGroups[] = 'diploma'; }
            if ($intershipEnabled) { $validationGroups[] = 'internship'; }
            if ($housingEnabled) { $validationGroups[] = 'housing'; }
            if ($housingEnabled && $housingContactEnabled) { $validationGroups[] = 'housing_contact'; }
            if ($transportEnabled) { $validationGroups[] = 'transport'; }
        }
        //\Doctrine\Common\Util\Debug::dump($validationGroups);
        
        $form = $this->createEditForm($opinion, $validationGroups);
        
        $form->bind($request, 10);
 
        if ($form->isValid()) {
            $opinion = $form->getData();
            $opinion->setPublished($isPublish);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($opinion);
            $manager->flush();  
            return new JsonResponse(array('status' => 'ok', 'action' => ($isPublish) ? 'publish' : 'save'));
        } else {
            return new JsonResponse(array('status' => 'error', 'errors' => $this->getErrorMessages($form), 'action' => ($isPublish) ? 'publish' : 'save'));
        }
    }
    
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }
    
    public function nullOrValue ($val) {
        return ($val) ? $val : null;
    }
    
    public function getLastYearSemesters () {
        $listY = array();
        $curYear = date('Y');
        $curMonth = date('m');
        if ($curMonth > 8) $listY[] = 'A'.$curYear;
        if ($curMonth > 2) $listY[] = 'P'.$curYear;
        for ($i=1; $i<4; ++$i) {
            $listY[] = 'A'.($curYear-$i);
            $listY[] = 'P'.($curYear-$i);
        }
        return $listY;
    }
}
