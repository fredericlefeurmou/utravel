<?php

namespace UTravel\OpinionBundle\Twig;

class TwigOpinionExtension extends \Twig_Extension
{
    private $em;
    
    public function __construct ($em) {
        $this->em = $em;
    }

    public function getGlobals()
    {
        $journeyTypes = $this->em->getRepository('UTravelOpinionBundle:JourneyType')->findAll();
        return array('semesters' => $this->getLastYearSemesters(), 'journeyTypes' => $journeyTypes, 
            'preferedCountries' => $this->getPreferedCountries());
    }

    public function getName()
    {
        return 'opinion_extension';
    }
    
    public function getLastYearSemesters () {
        $listY = array();
        $curYear = date('Y');
        $curMonth = date('m');
        if ($curMonth > 8) $listY[] = 'A'.$curYear;
        if ($curMonth > 2) $listY[] = 'P'.$curYear;
        for ($i=1; $i<3; ++$i) {
            $listY[] = 'A'.($curYear-$i);
            $listY[] = 'P'.($curYear-$i);
        }
        return array_slice($listY, 0, 4);
    }
    
    public function getPreferedCountries () {
        $repository = $this->em->getRepository('UTravelOpinionBundle:Opinion');
        $qb = $repository->createQueryBuilder('o');
        $qb->select(array('o.country', 'COUNT(o.id) AS nb'));
        $qb->andWhere('o.published = 1');
        $qb->groupBy('o.country');
        $qb->addOrderBy('nb', 'DESC');
        $qb->setMaxResults(4);
        
        $res = $qb->getQuery()->getResult();
        
        $countries = \Symfony\Component\Locale\Locale::getDisplayCountries('en');
        foreach ($res as &$row) {
            $row['name'] = $countries[$row['country']];
        }
        
        return $res;
    }
}