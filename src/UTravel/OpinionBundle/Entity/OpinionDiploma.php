<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OpinionDiploma extends OpinionStudy
{
    public $journeyType = 2;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $diploma;

    public function getGenericTitle () {
        $title = 'Double diplôme - ';
        if ($this->university != null) {
            $title .= $this->university->getName();
        } else {
            $title .= $this->getCreatedAt()->format('d/m/Y');
        }
        return $title;
    }

    public function getTitle () {
        if ($this->getUniversity() != null) {
            return $this->getUniversity()->getName() . ' (' . $this->getFullSemester() . ')';
        }
        return '';
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
         parent::prePersist();
    }


    /**
     * Set diploma
     *
     * @param string $diploma
     * @return OpinionDiploma
     */
    public function setDiploma($diploma)
    {
        $this->diploma = $diploma;

        return $this;
    }

    /**
     * Get diploma
     *
     * @return string 
     */
    public function getDiploma()
    {
        return $this->diploma;
    }
}
