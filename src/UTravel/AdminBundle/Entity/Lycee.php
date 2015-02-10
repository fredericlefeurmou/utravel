<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lycee
 *
 * @ORM\Table(name="lycees_francais")
 * @ORM\Entity
 */
class Lycee
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="proviseur", type="string", length=255)
     */
    private $proviseur;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    public function __construct($pays, $lycee)
    {
        $this->setPays($pays);

        $this->setNom($lycee['nom']);
        $this->setProviseur($lycee['proviseur']);
        $this->setVille($lycee['ville']);
        $this->setAdresse($lycee['adr']);
        $this->setCp($lycee['cp']);
        $this->setTelephone($lycee['tel']);
        $this->setEmail($lycee['email']);
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
     * Set nom
     *
     * @param string $nom
     * @return Lycee
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set proviseur
     *
     * @param string $proviseur
     * @return Lycee
     */
    public function setProviseur($proviseur)
    {
        $this->proviseur = $proviseur;

        return $this;
    }

    /**
     * Get proviseur
     *
     * @return string 
     */
    public function getProviseur()
    {
        return $this->proviseur;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Lycee
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
     * Set adresse
     *
     * @param string $adresse
     * @return Lycee
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
     * Set cp
     *
     * @param string $cp
     * @return Lycee
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
     * Set email
     *
     * @param string $email
     * @return Lycee
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
     * Set telephone
     *
     * @param string $telephone
     * @return Lycee
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
     * Set pays
     *
     * @param \UTravel\AdminBundle\Entity\Pays $pays
     * @return Lycee
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
