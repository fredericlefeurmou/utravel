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

class AccountController extends Controller
{
    public function accountAction(Request $request)
    {
        $session = $request->getSession();
        if (User::isAuthentificated($session)) {
            $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
            return $this->render('UTravelOpinionBundle:Account:my_account.html.twig', array('user' => $user));
        } else {
            return $this->redirect($this->generateUrl('utravel_login', array('redirect_url' => 'mon_compte')));
        }
    }
    
    public function accountChangeMailAction(Request $request)
    {
        $session = $request->getSession();
        if (User::isAuthentificated($session)) {
            $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
            if ($user != null) {
                $mail = $request->request->get('mail');
                $errors = $this->get('validator')->validateValue($mail, new \Symfony\Component\Validator\Constraints\Email());
                if ($errors->count() === 0) {
                    $user->setMail($mail);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($user);
                    $manager->flush();  
                    return new JsonResponse(array('status' => 'ok', 'mail' => $mail));
                } else {
                    return new JsonResponse(array('status' => 'error', 'error' => 'Adresse mail invalide'));
                }
            }
        } else {
            return $this->redirect($this->generateUrl('utravel_login'));
        }
    }
    
    public function accountChangePublicAction(Request $request)
    {
        $session = $request->getSession();
        if (User::isAuthentificated($session)) {
            $user = $this->getDoctrine()->getRepository('UTravelOpinionBundle:User')->getCurrentUser($session);
            if ($user != null) {
                $user->setPublic(!$user->getPublic());
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();  
                return new JsonResponse(array('status' => 'ok', 'public' => $user->getPublic()));
            }
        } else {
            return $this->redirect($this->generateUrl('utravel_login'));
        }
    }
}
