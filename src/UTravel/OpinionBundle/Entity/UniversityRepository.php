<?php

namespace UTravel\OpinionBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * UniversityRepository
 */
class UniversityRepository extends EntityRepository
{
    public function displayUniversities()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
           ->from('UTravelOpinionBundle:University', 'u')
           ->where('u.partnership = :value')
           ->setParameter('value', true)
           ->orderBy('u.country', 'ASC');
        return $qb->getQuery()->getResult();
    }
}