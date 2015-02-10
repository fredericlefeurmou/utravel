<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bureau
 *
 * @ORM\Table(name="bureaux_nationaux")
 * @ORM\Entity
 */
class Bureau
{
    /**
     * @ORM\ManyToOne(targetEntity="UTravel\AdminBundle\Entity\Pays")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false)
     */
    private $pays;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_ubifrance", type="boolean")
     * @ORM\Id
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
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


    public function __construct($pays, $isUbifrance, $form)
    {
        $this->setPays($pays);
        $this->setIsUbifrance($isUbifrance);

        if ($isUbifrance)
        {
            $this->setVille($form->get('ubifrance_ville'));
            $this->setCp($form->get('ubifrance_cp'));
            $this->setTelephone($form->get('ubifrance_telephone'));
            $this->setAdresse($form->get('ubifrance_adresse'));
            $this->setEmail($form->get('ubifrance_email'));
        }
        else
        {
            $this->setVille($form->get('chcom_ville'));
            $this->setCp($form->get('chcom_cp'));
            $this->setTelephone($form->get('chcom_telephone'));
            $this->setAdresse($form->get('chcom_adresse'));
            $this->setEmail($form->get('chcom_email'));
        }
    }

    public function reset($form)
    {
        if ($this->getIsUbifrance())
        {
            $this->setVille($form->get('ubifrance_ville'));
            $this->setCp($form->get('ubifrance_cp'));
            $this->setTelephone($form->get('ubifrance_telephone'));
            $this->setAdresse($form->get('ubifrance_adresse'));
            $this->setEmail($form->get('ubifrance_email'));
        }
        else
        {
            $this->setVille($form->get('chcom_ville'));
            $this->setCp($form->get('chcom_cp'));
            $this->setTelephone($form->get('chcom_telephone'));
            $this->setAdresse($form->get('chcom_adresse'));
            $this->setEmail($form->get('chcom_email'));
        }
        
        return $this;
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
     * @return Bureau
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
     * @return Bureau
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
     * @return Bureau
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
     * @return Bureau
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
     * @return Bureau
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
     * Set email
     *
     * @param string $email
     * @return Bureau
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
     * @return Bureau
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
