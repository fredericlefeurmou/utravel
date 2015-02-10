<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Locale\Locale;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="journeyType", type="smallint")
 * @ORM\DiscriminatorMap({1 = "OpinionStudy", 2 = "OpinionDiploma", 3 = "OpinionInternship", 4 = "OpinionPlacement"})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="opinion", indexes={@ORM\Index(name="journey_idx", columns={"journeyType"})})
 */
abstract class Opinion
{
    public $journeyType = 0;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    // General section 
    /**
     * @ORM\Column(type="integer", nullable=true)
     */   
    private $year;
    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $semester;
    /**
     * @ORM\ManyToOne(targetEntity="Branch")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id")
     */
    private $branch;
    /**
     * @ORM\ManyToOne(targetEntity="BranchSpecialization")
     * @ORM\JoinColumn(name="specialization_id", referencedColumnName="id")
     */
    private $specialization;
    /**
     * @ORM\ManyToOne(targetEntity="MainLanguage")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $generalComment;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $generalAdvice;
    
    // Notes
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $noteGeneral;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $noteHousing;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $noteLife;
    
    // Housing 
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $housingEnabled = true;
    /**
     * @ORM\ManyToOne(targetEntity="HousingType")
     * @ORM\JoinColumn(name="housing_type_id", referencedColumnName="id")
     */
    private $housingType;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $housingRent;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $housingComment;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $housingContactEnabled = true;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $housingOrganization;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $housingAdress1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $housingAdress2;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $housingPostalCode;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $housingCity;
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $housingCountry;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $housingEmail;
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $housingPhone;
    
    // Life
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lifeComment;
    
    // Transport
    /**
     * @ORM\Column(type="boolean")
     */
    private $transportEnabled = true;
    /**
     * @ORM\ManyToOne(targetEntity="TransportType")
     * @ORM\JoinColumn(name="transport_type_id", referencedColumnName="id")
     */
    private $transportType;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $transportCompany;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transportPrice;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $transportComment;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $transportOnSite;
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="OpinionVote", mappedBy="opinion")
     */
    private $votes;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="opinions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $published = false;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt; 
    
    public static $journeyTypeArray = null;
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->createdAt = new \DateTime();
    }
    
    protected function calculateScore () {
        $score = 20;
        if ($this->getHousingEnabled()) $score += 5;
        if ($this->getHousingContactEnabled()) $score += 2;
        if ($this->getTransportEnabled()) $score += 5;
        $score += strlen($this->getGeneralComment())/40;
        $score += strlen($this->getLifeComment())/50;
    }
    
    public function getTypeAsString () {
        foreach (self::$journeyTypeArray as $jt) {
            if ($this->journeyType == $jt->getId()) {
                return $jt->getName();
            }
        }
        return '';
    }
    
    abstract public function getGenericTitle ();
    abstract public function getTitle ();
    
    public function setFullSemester ($semester) {
        $this->semester = $semester[0];
        $this->year = intval(substr($semester, 1));
    }
    public function getFullSemester () {
        return $this->semester . $this->year;
    }
    public function getLocalCountry () {
        $countries = Locale::getDisplayCountries('fr');
        return $countries[$this->country];
    }
    public function getHousingLocalCountry () {
        $countries = Locale::getDisplayCountries('fr');
        return $countries[$this->housingCountry];
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
     * Set year
     *
     * @param integer $year
     * @return Opinion
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set semester
     *
     * @param string $semester
     * @return Opinion
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return string 
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Opinion
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Opinion
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set generalComment
     *
     * @param string $generalComment
     * @return Opinion
     */
    public function setGeneralComment($generalComment)
    {
        $this->generalComment = $generalComment;

        return $this;
    }

    /**
     * Get generalComment
     *
     * @return string 
     */
    public function getGeneralComment()
    {
        return $this->generalComment;
    }

    /**
     * Set noteGeneral
     *
     * @param integer $noteGeneral
     * @return Opinion
     */
    public function setNoteGeneral($noteGeneral)
    {
        $this->noteGeneral = $noteGeneral;

        return $this;
    }

    /**
     * Get noteGeneral
     *
     * @return integer 
     */
    public function getNoteGeneral()
    {
        return $this->noteGeneral;
    }

    /**
     * Set noteHousing
     *
     * @param integer $noteHousing
     * @return Opinion
     */
    public function setNoteHousing($noteHousing)
    {
        $this->noteHousing = $noteHousing;

        return $this;
    }

    /**
     * Get noteHousing
     *
     * @return integer 
     */
    public function getNoteHousing()
    {
        return $this->noteHousing;
    }

    /**
     * Set noteLife
     *
     * @param integer $noteLife
     * @return Opinion
     */
    public function setNoteLife($noteLife)
    {
        $this->noteLife = $noteLife;

        return $this;
    }

    /**
     * Get noteLife
     *
     * @return integer 
     */
    public function getNoteLife()
    {
        return $this->noteLife;
    }

    /**
     * Set housingRent
     *
     * @param integer $housingRent
     * @return Opinion
     */
    public function setHousingRent($housingRent)
    {
        $this->housingRent = $housingRent;

        return $this;
    }

    /**
     * Get housingRent
     *
     * @return integer 
     */
    public function getHousingRent()
    {
        return $this->housingRent;
    }

    /**
     * Set housingComment
     *
     * @param string $housingComment
     * @return Opinion
     */
    public function setHousingComment($housingComment)
    {
        $this->housingComment = $housingComment;

        return $this;
    }

    /**
     * Get housingComment
     *
     * @return string 
     */
    public function getHousingComment()
    {
        return $this->housingComment;
    }

    /**
     * Set lifeComment
     *
     * @param string $lifeComment
     * @return Opinion
     */
    public function setLifeComment($lifeComment)
    {
        $this->lifeComment = $lifeComment;

        return $this;
    }

    /**
     * Get lifeComment
     *
     * @return string 
     */
    public function getLifeComment()
    {
        return $this->lifeComment;
    }

    /**
     * Set transportCompany
     *
     * @param string $transportCompany
     * @return Opinion
     */
    public function setTransportCompany($transportCompany)
    {
        $this->transportCompany = $transportCompany;

        return $this;
    }

    /**
     * Get transportCompany
     *
     * @return string 
     */
    public function getTransportCompany()
    {
        return $this->transportCompany;
    }

    /**
     * Set transportPrice
     *
     * @param integer $transportPrice
     * @return Opinion
     */
    public function setTransportPrice($transportPrice)
    {
        $this->transportPrice = $transportPrice;

        return $this;
    }

    /**
     * Get transportPrice
     *
     * @return integer 
     */
    public function getTransportPrice()
    {
        return $this->transportPrice;
    }

    /**
     * Set transportComment
     *
     * @param string $transportComment
     * @return Opinion
     */
    public function setTransportComment($transportComment)
    {
        $this->transportComment = $transportComment;

        return $this;
    }

    /**
     * Get transportComment
     *
     * @return string 
     */
    public function getTransportComment()
    {
        return $this->transportComment;
    }

    /**
     * Set transportOnSite
     *
     * @param string $transportOnSite
     * @return Opinion
     */
    public function setTransportOnSite($transportOnSite)
    {
        $this->transportOnSite = $transportOnSite;

        return $this;
    }

    /**
     * Get transportOnSite
     *
     * @return string 
     */
    public function getTransportOnSite()
    {
        return $this->transportOnSite;
    }

    /**
     * Set type
     *
     * @param \UTravel\OpinionBundle\Entity\JourneyType $type
     * @return Opinion
     */
    public function setType(\UTravel\OpinionBundle\Entity\JourneyType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \UTravel\OpinionBundle\Entity\JourneyType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set branch
     *
     * @param \UTravel\OpinionBundle\Entity\Branch $branch
     * @return Opinion
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

    /**
     * Set specialization
     *
     * @param \UTravel\OpinionBundle\Entity\BranchSpecialization $specialization
     * @return Opinion
     */
    public function setSpecialization(\UTravel\OpinionBundle\Entity\BranchSpecialization $specialization = null)
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * Get specialization
     *
     * @return \UTravel\OpinionBundle\Entity\BranchSpecialization 
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }

    /**
     * Set language
     *
     * @param \UTravel\OpinionBundle\Entity\MainLanguage $language
     * @return Opinion
     */
    public function setLanguage(\UTravel\OpinionBundle\Entity\MainLanguage $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \UTravel\OpinionBundle\Entity\MainLanguage 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set housingType
     *
     * @param \UTravel\OpinionBundle\Entity\HousingType $housingType
     * @return Opinion
     */
    public function setHousingType(\UTravel\OpinionBundle\Entity\HousingType $housingType = null)
    {
        $this->housingType = $housingType;

        return $this;
    }

    /**
     * Get housingType
     *
     * @return \UTravel\OpinionBundle\Entity\HousingType 
     */
    public function getHousingType()
    {
        return $this->housingType;
    }

    /**
     * Set transportType
     *
     * @param \UTravel\OpinionBundle\Entity\TransportType $transportType
     * @return Opinion
     */
    public function setTransportType(\UTravel\OpinionBundle\Entity\TransportType $transportType = null)
    {
        $this->transportType = $transportType;

        return $this;
    }

    /**
     * Get transportType
     *
     * @return \UTravel\OpinionBundle\Entity\TransportType 
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * Set author
     *
     * @param \UTravel\OpinionBundle\Entity\User $author
     * @return Opinion
     */
    public function setAuthor(\UTravel\OpinionBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \UTravel\OpinionBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set housingOrganization
     *
     * @param string $housingOrganization
     * @return Opinion
     */
    public function setHousingOrganization($housingOrganization)
    {
        $this->housingOrganization = $housingOrganization;

        return $this;
    }

    /**
     * Get housingOrganization
     *
     * @return string 
     */
    public function getHousingOrganization()
    {
        return $this->housingOrganization;
    }

    /**
     * Set housingAdress1
     *
     * @param string $housingAdress1
     * @return Opinion
     */
    public function setHousingAdress1($housingAdress1)
    {
        $this->housingAdress1 = $housingAdress1;

        return $this;
    }

    /**
     * Get housingAdress1
     *
     * @return string 
     */
    public function getHousingAdress1()
    {
        return $this->housingAdress1;
    }

    /**
     * Set housingAdress2
     *
     * @param string $housingAdress2
     * @return Opinion
     */
    public function setHousingAdress2($housingAdress2)
    {
        $this->housingAdress2 = $housingAdress2;

        return $this;
    }

    /**
     * Get housingAdress2
     *
     * @return string 
     */
    public function getHousingAdress2()
    {
        return $this->housingAdress2;
    }

    /**
     * Set housingPostalCode
     *
     * @param string $housingPostalCode
     * @return Opinion
     */
    public function setHousingPostalCode($housingPostalCode)
    {
        $this->housingPostalCode = $housingPostalCode;

        return $this;
    }

    /**
     * Get housingPostalCode
     *
     * @return string 
     */
    public function getHousingPostalCode()
    {
        return $this->housingPostalCode;
    }

    /**
     * Set housingCity
     *
     * @param string $housingCity
     * @return Opinion
     */
    public function setHousingCity($housingCity)
    {
        $this->housingCity = $housingCity;

        return $this;
    }

    /**
     * Get housingCity
     *
     * @return string 
     */
    public function getHousingCity()
    {
        return $this->housingCity;
    }

    /**
     * Set housingConutry
     *
     * @param string $housingConutry
     * @return Opinion
     */
    public function setHousingConutry($housingConutry)
    {
        $this->housingConutry = $housingConutry;

        return $this;
    }

    /**
     * Get housingConutry
     *
     * @return string 
     */
    public function getHousingConutry()
    {
        return $this->housingConutry;
    }

    /**
     * Set housingEmail
     *
     * @param string $housingEmail
     * @return Opinion
     */
    public function setHousingEmail($housingEmail)
    {
        $this->housingEmail = $housingEmail;

        return $this;
    }

    /**
     * Get housingEmail
     *
     * @return string 
     */
    public function getHousingEmail()
    {
        return $this->housingEmail;
    }

    /**
     * Set housingPhone
     *
     * @param string $housingPhone
     * @return Opinion
     */
    public function setHousingPhone($housingPhone)
    {
        $this->housingPhone = $housingPhone;

        return $this;
    }

    /**
     * Get housingPhone
     *
     * @return string 
     */
    public function getHousingPhone()
    {
        return $this->housingPhone;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Opinion
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Opinion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set housingCountry
     *
     * @param string $housingCountry
     * @return Opinion
     */
    public function setHousingCountry($housingCountry)
    {
        $this->housingCountry = $housingCountry;

        return $this;
    }

    /**
     * Get housingCountry
     *
     * @return string 
     */
    public function getHousingCountry()
    {
        return $this->housingCountry;
    }

    /**
     * Set housingEnabled
     *
     * @param boolean $housingEnabled
     * @return Opinion
     */
    public function setHousingEnabled($housingEnabled)
    {
        $this->housingEnabled = $housingEnabled;

        return $this;
    }

    /**
     * Get housingEnabled
     *
     * @return boolean 
     */
    public function getHousingEnabled()
    {
        return $this->housingEnabled;
    }

    /**
     * Set transportEnabled
     *
     * @param boolean $transportEnabled
     * @return Opinion
     */
    public function setTransportEnabled($transportEnabled)
    {
        $this->transportEnabled = $transportEnabled;

        return $this;
    }

    /**
     * Get transportEnabled
     *
     * @return boolean 
     */
    public function getTransportEnabled()
    {
        return $this->transportEnabled;
    }

    /**
     * Set housingContactEnabled
     *
     * @param boolean $housingContactEnabled
     * @return Opinion
     */
    public function setHousingContactEnabled($housingContactEnabled)
    {
        $this->housingContactEnabled = $housingContactEnabled;

        return $this;
    }

    /**
     * Get housingContactEnabled
     *
     * @return boolean 
     */
    public function getHousingContactEnabled()
    {
        return $this->housingContactEnabled;
    }

    /**
     * Set generalAdvice
     *
     * @param string $generalAdvice
     * @return Opinion
     */
    public function setGeneralAdvice($generalAdvice)
    {
        $this->generalAdvice = $generalAdvice;

        return $this;
    }

    /**
     * Get generalAdvice
     *
     * @return string 
     */
    public function getGeneralAdvice()
    {
        return $this->generalAdvice;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add votes
     *
     * @param \UTravel\OpinionBundle\Entity\OpinionVote $votes
     * @return Opinion
     */
    public function addVote(\UTravel\OpinionBundle\Entity\OpinionVote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \UTravel\OpinionBundle\Entity\OpinionVote $votes
     */
    public function removeVote(\UTravel\OpinionBundle\Entity\OpinionVote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
