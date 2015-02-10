<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OpinionInternship extends Opinion
{
    public $journeyType = 3;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $noteInternship;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $internshipComment;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $companyName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $companyLink;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $companyField;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $internshipTitle;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $internshipDescription;

    public function getGenericTitle () {
        $title = 'Stage en entreprise - ';
        if ($this->companyName != null && $this->companyName != '') {
            $title .= $this->companyName;
        } else {
            $title .= $this->getCreatedAt()->format('d/m/Y');
        }
        return $title;
    }

    public function getTitle () {
        return 'Stage en entreprise - ' . $this->companyName . ' (' . $this->getSemester() . $this->getYear() . ')';
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
         parent::prePersist();
    }

    /**
     * Set noteInternship
     *
     * @param integer $noteInternship
     * @return OpinionInternship
     */
    public function setNoteInternship($noteInternship)
    {
        $this->noteInternship = $noteInternship;

        return $this;
    }

    /**
     * Get noteInternship
     *
     * @return integer 
     */
    public function getNoteInternship()
    {
        return $this->noteInternship;
    }

    /**
     * Set internshipComment
     *
     * @param string $internshipComment
     * @return OpinionInternship
     */
    public function setInternshipComment($internshipComment)
    {
        $this->internshipComment = $internshipComment;

        return $this;
    }

    /**
     * Get internshipComment
     *
     * @return string 
     */
    public function getInternshipComment()
    {
        return $this->internshipComment;
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
     * Set companyLink
     *
     * @param string $companyLink
     * @return OpinionInternship
     */
    public function setCompanyLink($companyLink)
    {
        $this->companyLink = $companyLink;

        return $this;
    }

    /**
     * Get companyLink
     *
     * @return string 
     */
    public function getCompanyLink()
    {
        return $this->companyLink;
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
     * Set internshipTitle
     *
     * @param string $internshipTitle
     * @return OpinionInternship
     */
    public function setInternshipTitle($internshipTitle)
    {
        $this->internshipTitle = $internshipTitle;

        return $this;
    }

    /**
     * Get internshipTitle
     *
     * @return string 
     */
    public function getInternshipTitle()
    {
        return $this->internshipTitle;
    }

    /**
     * Set internshipDescription
     *
     * @param string $internshipDescription
     * @return OpinionInternship
     */
    public function setInternshipDescription($internshipDescription)
    {
        $this->internshipDescription = $internshipDescription;

        return $this;
    }

    /**
     * Get internshipDescription
     *
     * @return string 
     */
    public function getInternshipDescription()
    {
        return $this->internshipDescription;
    }
}
