<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OpinionPlacement extends Opinion
{
    public $journeyType = 4;

    public function getGenericTitle () {
        $title = 'Semestre de césure - ';
        $country = $this->getLocalCountry();
        if ($country != null && $country != '') {
            $title .= $country;
        } else {
            $title .= $this->getCreatedAt()->format('d/m/Y');
        }
        return $title;
    }

    public function getTitle () {
        return 'Césure - ' . $this->getLocalCountry() . ' (' . $this->getFullSemester() . ')';
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
         parent::prePersist();
    }
    

    /**
     * Set noteIntership
     *
     * @param integer $noteIntership
     * @return OpinionInternship
     */
    public function setNoteIntership($noteIntership)
    {
        $this->noteIntership = $noteIntership;

        return $this;
    }

    /**
     * Get noteIntership
     *
     * @return integer 
     */
    public function getNoteIntership()
    {
        return $this->noteIntership;
    }

    /**
     * Set intershipComment
     *
     * @param string $intershipComment
     * @return OpinionInternship
     */
    public function setIntershipComment($intershipComment)
    {
        $this->intershipComment = $intershipComment;

        return $this;
    }

    /**
     * Get intershipComment
     *
     * @return string 
     */
    public function getIntershipComment()
    {
        return $this->intershipComment;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return OpinionInternship
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyField
     *
     * @param string $companyField
     * @return OpinionInternship
     */
    public function setCompanyField($companyField)
    {
        $this->companyField = $companyField;

        return $this;
    }

    /**
     * Get companyField
     *
     * @return string 
     */
    public function getCompanyField()
    {
        return $this->companyField;
    }

    /**
     * Set intershipTitle
     *
     * @param string $intershipTitle
     * @return OpinionInternship
     */
    public function setIntershipTitle($intershipTitle)
    {
        $this->intershipTitle = $intershipTitle;

        return $this;
    }

    /**
     * Get intershipTitle
     *
     * @return string 
     */
    public function getIntershipTitle()
    {
        return $this->intershipTitle;
    }

    /**
     * Set intershipDescription
     *
     * @param string $intershipDescription
     * @return OpinionInternship
     */
    public function setIntershipDescription($intershipDescription)
    {
        $this->intershipDescription = $intershipDescription;

        return $this;
    }

    /**
     * Get intershipDescription
     *
     * @return string 
     */
    public function getIntershipDescription()
    {
        return $this->intershipDescription;
    }
}
