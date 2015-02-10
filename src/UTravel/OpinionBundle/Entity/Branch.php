<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="branch")
 */
class Branch {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $abbreviation;
    
    /**
     * @ORM\OneToMany(targetEntity="BranchSpecialization", mappedBy="branch")
     */
    protected $specializations;
    
    public function __toString() {
        return $this->name;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specializations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Branch
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add specializations
     *
     * @param \UTravel\OpinionBundle\Entity\BranchSpecialization $specializations
     * @return Branch
     */
    public function addSpecialization(\UTravel\OpinionBundle\Entity\BranchSpecialization $specializations)
    {
        $this->specializations[] = $specializations;

        return $this;
    }

    /**
     * Remove specializations
     *
     * @param \UTravel\OpinionBundle\Entity\BranchSpecialization $specializations
     */
    public function removeSpecialization(\UTravel\OpinionBundle\Entity\BranchSpecialization $specializations)
    {
        $this->specializations->removeElement($specializations);
    }

    /**
     * Get specializations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSpecializations()
    {
        return $this->specializations;
    }

    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return Branch
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string 
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
}
