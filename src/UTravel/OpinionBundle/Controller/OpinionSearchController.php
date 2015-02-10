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

class OpinionSearchController extends Controller
{
    private $nbResultPerPage = 20;
    
    public function getFormSearch ($defValues = array()) {
        $sem =  $this->getLastYearSemesters(); 
        $form = $this->createFormBuilder()->getForm();
        
        $form->add('journeyType', 'entity', array(
                'class' => 'UTravelOpinionBundle:JourneyType',
                'label' => 'Type de séjour : ',
                'required' => false,
                'empty_value' => 'Indifférent',
                'multiple' => true,
                'data' => (isset($defValues['type'])) ? $defValues['type'] : null,
                'empty_data' => null))
             ->add('branch', 'entity', array(
                'class' => 'UTravelOpinionBundle:Branch',
                'label' => 'Branche : ',
                'required' => false,
                'multiple' => true,
                'empty_value' => 'Tronc commun',
                'empty_data' => null))
            ->add('specialization', 'entity', array(
                'class' => 'UTravelOpinionBundle:BranchSpecialization',
                'group_by' => 'branch',
                'label' => 'Filière : ',
                'required' => false,
                'multiple' => true,
                'empty_value' => 'Aucune',
                'empty_data' => null))
            ->add('fullSemester', 'choice', array(
                'choices' => array_combine($sem, $sem),
                'required' => false,
                'multiple' => true,
                'data' => (isset($defValues['fullSemester'])) ? $defValues['fullSemester'] : null,
                'label' => 'Semestre : '))
            ->add('country', 'country', array(
                'multiple' => true,
                'data' => (isset($defValues['fullSemester'])) ? $defValues['countries'] : null,
                'label' => 'Pays : '))
            ->add('language', 'entity', array(
                'class' => 'UTravelOpinionBundle:MainLanguage',
                'multiple' => true,
                'required' => false,
                'label' => 'Langue utilisée : ',
                'required' => false,
                'empty_value' => 'Aucune'))
            ->add('page', 'hidden', array())
            ->add('returnHtml', 'hidden', array());
        
        return $form;
    }
    
    public function getLastYearSemesters () {
        $listY = array();
        $curYear = date('Y');
        $curMonth = date('m');
        if ($curMonth > 8) $listY[] = 'A'.$curYear;
        if ($curMonth > 2) $listY[] = 'P'.$curYear;
        for ($i=1; $i<6; ++$i) {
            $listY[] = 'A'.($curYear-$i);
            $listY[] = 'P'.($curYear-$i);
        }
        return $listY;
    }
    
    public function indexAction(Request $req)
    {
        $typeIds = explode(',', $req->query->get('type'));
        $types = $this->getDoctrine()->getRepository('UTravelOpinionBundle:JourneyType')->findById($typeIds);
        $defValues = array(
            'type' => $types, 
            'fullSemester' => explode(',', $req->query->get('sem')), 
            'countries' => explode(',', $req->query->get('country')));
        $form = $this->getFormSearch($defValues)->createView();
        $journeyTypes = $this->getDoctrine()->getRepository('UTravelOpinionBundle:JourneyType')->findAll();
        return $this->render('UTravelOpinionBundle:OpinionSearch:index.html.twig', array('journey_types' => $journeyTypes, 'form' => $form));
    }
    
    private function performSearch ($data) {
         $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
        $qb = $repository->createQueryBuilder('o');
        
        $qb->andWhere($qb->expr()->eq('o.published', true));
        
        if (count($data['fullSemester']) > 0) {
           $fsExpr = $qb->expr()->orX();
            foreach ($data['fullSemester'] as $fs) {
                $sem = $fs[0];
                $year = substr($fs, 1);
                $fsExpr = $fsExpr->add($qb->expr()->andX(
                    $qb->expr()->eq('o.semester', $qb->expr()->literal($sem)), 
                    $qb->expr()->eq('o.year', $year)
                ));
            }
            $qb->andWhere($fsExpr);
        }
        
        
        if (count($data['journeyType']) > 0) {
            $assoc = array(JourneyType::$Studying => 'UTravel\OpinionBundle\Entity\OpinionStudy',
                JourneyType::$Diploma => 'UTravel\OpinionBundle\Entity\OpinionDiploma',
                JourneyType::$Internship => 'UTravel\OpinionBundle\Entity\OpinionInternship',
                JourneyType::$Placement => 'UTravel\OpinionBundle\Entity\OpinionPlacement');
            $jtExpr = $qb->expr();
            $ws = array();
            foreach ($data['journeyType'] as $jt) {
                $ws[] = 'o INSTANCE OF '.$assoc[$jt->getId()];
            }
            $qb->andWhere('('.implode(' OR ', $ws).')');
        }
        if (count($data['branch']) > 0) {
            $branch = array();
            foreach ($data['branch'] as $b) {
                $branch[] = $b->getId();
            }
            $qb->andWhere('o.branch IN (:branch)')
                    ->setParameter('branch', $branch);
        }
        if (count($data['specialization']) > 0) {
            $specialization = array();
            foreach ($data['specialization'] as $b) {
                $specialization[] = $b->getId();
            }
            $qb->andWhere('o.specialization IN (:specialization)')
                    ->setParameter('specialization', $specialization);
        }
        if (count($data['language']) > 0) {
            $language = array();
            foreach ($data['language'] as $b) {
                $language[] = $b->getId();
            }
            $qb->andWhere('o.language IN (:language)')
                    ->setParameter('language', $language);
        }
        if (count($data['country']) > 0) {
            $qb->andWhere('o.country IN (:country)')
                    ->setParameter('country', $data['country']);
        }
        
        /*\Doctrine\Common\Util\Debug::dump($qb->select('COUNT(o.id)')
                    ->getQuery()->getSql());*/
        
        $count =  intval($qb->select('COUNT(o.id)')
                    ->getQuery()
                    ->getSingleScalarResult());
        
        $qb->leftJoin('UTravel\OpinionBundle\Entity\OpinionVote', 'v', 'WITH', 'v.opinion = o.id');
        $qb->groupBy('o.id');
        $qb->addOrderBy('nbVote', 'DESC');
        
        $result = $qb->select(array('o', 'COUNT(v.opinion) AS nbVote'))
                ->setFirstResult( $data['offset'] )
                ->setMaxResults( $data['limit'] )
                ->getQuery()
                ->getResult();
        
        $res = array();
        foreach ($result as $row) {
            $res[] = $row[0];
        }
        
        return array('result' => $res, 'count' => $count);
    }
    
    public function searchAction(Request $request)
    {
        $form = $this->getFormSearch();
        $form->bind($request, 10);
        $data = $form->getData();
        
        $page = intval($data['page']);
        $lb = $page * $this->nbResultPerPage;
        $ub = $lb + $this->nbResultPerPage;
        
        $data['offset'] = $lb;
        $data['limit'] = $ub;
        
        $array = $this->performSearch($data);
        $result = $array['result'];
        
        $returnHtml = $data['returnHtml'];
        
        $ids = array();
        $html = '';
        foreach ($result as $op) {
            $ids[] = $op->getId();
        }
        
        \UTravel\OpinionBundle\Entity\Opinion::$journeyTypeArray = $this->getDoctrine()->getRepository('UTravelOpinionBundle:JourneyType')->findAll();
        
        if ($returnHtml) {
            $html = $this->renderView('UTravelOpinionBundle:OpinionSearch:searchResult.html.twig', array('opinions' => $result));
        }
       
        
        return new JsonResponse(array('status' => 'ok', 'ids' => $ids, 'count' => $array['count'], 
            'offset' => $lb, 'page' => $page, 'nbResultPage' => $this->nbResultPerPage, 'html' => $html));
    }
    
    public function displayOpinionAction($opinion_id)
    {
        $repository = $this->getDoctrine()->getRepository('UTravelOpinionBundle:Opinion');
        $opinion = $repository->find($opinion_id);
        if ($opinion != null && $opinion->getPublished()) {
            $repositoryJourney = $this->getDoctrine()->getRepository('UTravelOpinionBundle:JourneyType');
            $journeyType = $repositoryJourney->find($opinion->journeyType);
            $userAuthentified = User::isAuthentificated($this->getRequest()->getSession());
            return $this->render('UTravelOpinionBundle:OpinionSearch:opinion.html.twig', array('opinion' => $opinion, 
                'journeyType' => $journeyType, 'userAuthentified' => $userAuthentified));
        } else {
            throw $this->createNotFoundException();
        }
    }
}
