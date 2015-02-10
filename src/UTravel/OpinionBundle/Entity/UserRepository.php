<?php

namespace UTravel\OpinionBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    public function getCurrentUser($session)
    {
        $login = $session->get('user_login_U7E2R');
        $user = $this->_em->getRepository('UTravelOpinionBundle:User')->findByLogin($login);

        return ($user == null) ? null : $user[0];
    }
}