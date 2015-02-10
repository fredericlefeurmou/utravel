<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="opinion_vote")
 */
class OpinionVote {
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=8)
     */
    protected $login;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Opinion", inversedBy="votes")
     * @ORM\JoinColumn(name="opinion_id", referencedColumnName="id")
     */
    protected $opinion;
    
    public function __toString() {
        return $this->login;
    }
    

    /**
     * Set login
     *
     * @param string $login
     * @return OpinionVote
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set opinion
     *
     * @param \UTravel\OpinionBundle\Entity\Opinion $opinion
     * @return OpinionVote
     */
    public function setOpinion(\UTravel\OpinionBundle\Entity\Opinion $opinion)
    {
        $this->opinion = $opinion;

        return $this;
    }

    /**
     * Get opinion
     *
     * @return \UTravel\OpinionBundle\Entity\Opinion 
     */
    public function getOpinion()
    {
        return $this->opinion;
    }
}
