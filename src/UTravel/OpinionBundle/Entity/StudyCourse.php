<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="study_course")
 */
class StudyCourse {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $evaluationFile;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpinionStudy", inversedBy="courses")
     * @ORM\JoinColumn(name="opinion_id", referencedColumnName="id", nullable=false)
     */
    protected $opinion;

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
     * Set title
     *
     * @param string $title
     * @return StudyCourse
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return StudyCourse
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set evaluationFile
     *
     * @param string $evaluationFile
     * @return StudyCourse
     */
    public function setEvaluationFile($evaluationFile)
    {
        $this->evaluationFile = $evaluationFile;

        return $this;
    }

    /**
     * Get evaluationFile
     *
     * @return string 
     */
    public function getEvaluationFile()
    {
        return $this->evaluationFile;
    }

    /**
     * Set opinion
     *
     * @param \UTravel\OpinionBundle\Entity\OpinionStudy $opinion
     * @return StudyCourse
     */
    public function setOpinion(\UTravel\OpinionBundle\Entity\OpinionStudy $opinion = null)
    {
        $this->opinion = $opinion;

        return $this;
    }

    /**
     * Get opinion
     *
     * @return \UTravel\OpinionBundle\Entity\OpinionStudy 
     */
    public function getOpinion()
    {
        return $this->opinion;
    }
}
