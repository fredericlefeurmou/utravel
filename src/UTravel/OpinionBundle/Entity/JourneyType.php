<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="journey_type")
 */
class JourneyType {
    public static $Studying = 1;
    public static $Diploma = 2;
    public static $Internship = 3;
    public static $Placement = 4;
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=25)
     */
    protected $name;
    
    public function __toString() {
        return $this->name;
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
     * @return JourneyType
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
     * Set id
     *
     * @param integer $id
     * @return JourneyType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
