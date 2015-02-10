<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="branch_specialization")
 */
class BranchSpecialization {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $abbreviation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="specializations")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id")
     */
    protected $branch;
    
    public function __toString() {
        return $this->abbreviation;
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
     * @return BranchSpecialization
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
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return BranchSpecialization
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

    /**
     * Set branch
     *
     * @param \UTravel\OpinionBundle\Entity\Branch $branch
     * @return BranchSpecialization
     */
    public function setBranch(\UTravel\OpinionBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \UTravel\OpinionBundle\Entity\Branch 
     */
    public function getBranch()
    {
        return $this->branch;
    }
}
