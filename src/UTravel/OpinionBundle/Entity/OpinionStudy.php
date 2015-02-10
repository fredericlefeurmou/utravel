<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OpinionStudy extends Opinion
{
    public $journeyType = 1;
    /**
     * @ORM\Column(type="smallint")
     */
    private $noteStudying;
    /**
     * @ORM\ManyToOne(targetEntity="University")
     * @ORM\JoinColumn(name="university_id", referencedColumnName="id")
     */
    private $university;
    /**
     * @ORM\Column(type="text")
     */
    private $studyingComment;
    /**
     * @ORM\OneToMany(targetEntity="StudyCourse", mappedBy="opinion", cascade={"persist", "remove"})
     */
    private $courses;

    public function getGenericTitle () {
        $title = 'Semestre d\'étude - ';
        if ($this->university != null) {
            $title .= $this->university->getName();
        } else {
            $title .= $this->getCreatedAt()->format('d/m/Y');
        }
        return $title;
    }

    public function getTitle () {
        if ($this->university != null) {
            return $this->university->getName() . ' (' . $this->getSemester() . $this->getYear() . ')';
        }
        return '';
    }
    
    public function getCity() {
        return $this->university->getCity();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
         parent::prePersist();
    }
    

    /**
     * Add courses
     *
     * @param \UTravel\OpinionBundle\Entity\StudyCourse $courses
     * @return OpinionStudy
     */
    public function addCourse(\UTravel\OpinionBundle\Entity\StudyCourse $courses)
    {
        $this->courses[] = $courses;
        $courses->setOpinion($this);
        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set noteStudying
     *
     * @param integer $noteStudying
     * @return OpinionStudy
     */
    public function setNoteStudying($noteStudying)
    {
        $this->noteStudying = $noteStudying;

        return $this;
    }

    /**
     * Get noteStudying
     *
     * @return integer
     */
    public function getNoteStudying()
    {
        return $this->noteStudying;
    }

    /**
     * Set studyingComment
     *
     * @param string $studyingComment
     * @return OpinionStudy
     */
    public function setStudyingComment($studyingComment)
    {
        $this->studyingComment = $studyingComment;

        return $this;
    }

    /**
     * Get studyingComment
     *
     * @return string
     */
    public function getStudyingComment()
    {
        return $this->studyingComment;
    }

    /**
     * Set university
     *
     * @param \UTravel\OpinionBundle\Entity\University $university
     * @return OpinionStudy
     */
    public function setUniversity(\UTravel\OpinionBundle\Entity\University $university = null)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return \UTravel\OpinionBundle\Entity\University
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Remove courses
     *
     * @param \UTravel\OpinionBundle\Entity\StudyCourse $courses
     */
    public function removeCourse(\UTravel\OpinionBundle\Entity\StudyCourse $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }
}
