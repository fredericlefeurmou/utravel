<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annexe
 *
 * @ORM\Table(name="annexes")
 * @ORM\Entity
 */
class Annexe
{
    /**
     * @ORM\ManyToOne(targetEntity="UTravel\AdminBundle\Entity\Pays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pays;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_ubifrance", type="boolean")
     */
    private $isUbifrance;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    public function __construct($pays, $isUbifrance, $annexe)
    {
        $this->setPays($pays);
        $this->setIsUbifrance($isUbifrance);

        $this->setVille($annexe['ville']);
        $this->setCp($annexe['cp']);
        $this->setTelephone($annexe['tel']);
        $this->setAdresse($annexe['adr']);
        $this->setEmail($annexe['email']);
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
     * Set isUbifrance
     *
     * @param boolean $isUbifrance
     * @return Annexe
     */
    public function setIsUbifrance($isUbifrance)
    {
        $this->isUbifrance = $isUbifrance;

        return $this;
    }

    /**
     * Get isUbifrance
     *
     * @return boolean 
     */
    public function getIsUbifrance()
    {
        return $this->isUbifrance;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Annexe
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return Annexe
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Annexe
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Annexe
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Annexe
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pays
     *
     * @param \UTravel\AdminBundle\Entity\Pays $pays
     * @return Annexe
     */
    public function setPays(\UTravel\AdminBundle\Entity\Pays $pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \UTravel\AdminBundle\Entity\Pays 
     */
    public function getPays()
    {
        return $this->pays;
    }
}
