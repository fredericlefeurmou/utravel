<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pays
 *
 * @ORM\Table(name="fiche_pays")
 * @ORM\Entity()
 */
class Pays
{
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
     * @ORM\Column(name="drapeau", type="string", length=255, nullable=true)
     */
    private $drapeau;

    /**
     * @var string
     *
     * @ORM\Column(name="capitale", type="string", length=255, nullable=true)
     */
    private $capitale;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=255, nullable=true)
     */
    private $langue;

    /**
     * @var integer
     *
     * @ORM\Column(name="superficie", type="integer", nullable=true)
     */
    private $superficie;

    /**
     * @var integer
     *
     * @ORM\Column(name="population", type="integer", nullable=true)
     */
    private $population;

    /**
     * @var string
     *
     * @ORM\Column(name="chef_etat", type="string", length=255, nullable=true)
     */
    private $chefEtat;

    /**
     * @var string
     *
     * @ORM\Column(name="jour_national", type="string", length=255, nullable=true)
     */
    private $jourNational;

    /**
     * @var string
     *
     * @ORM\Column(name="monnaie", type="string", length=255, nullable=true)
     */
    private $monnaie;

    /**
     * @var string
     *
     * @ORM\Column(name="taux", type="string", nullable=true)
     */
    private $taux;

    /**
     * @var string
     *
     * @ORM\Column(name="ubifrance_dir", type="string", length=255, nullable=true)
     */
    private $ubifranceDir;

    /**
     * @var string
     *
     * @ORM\Column(name="commerce_dir", type="string", length=255, nullable=true)
     */
    private $commerceDir;

    /**
     * @ORM\OneToOne(targetEntity="UTravel\AdminBundle\Entity\ListeNumeros", cascade={"persist", "remove"})
     */
    private $listeNumeros;

    /**
     * @ORM\OneToOne(targetEntity="UTravel\AdminBundle\Entity\Ambassade", cascade={"persist", "remove"})
     */
    private $ambassade;

    /**
     * @ORM\OneToOne(targetEntity="UTravel\AdminBundle\Entity\Campus", cascade={"persist", "remove"})
     */
    private $campusFrance;

    public function __construct($form, $listeNumeros = null, $ambassade = null, $campusFrance = null)
    {
        $this->setNom($form->get('nom'));
        $this->setCapitale($form->get('capitale'));
        $this->setLangue($form->get('langue'));
        $this->setSuperficie(preg_replace("#\D#", "", $form->get('superficie')));
        $this->setPopulation(preg_replace("#\D#", "", $form->get('population')));
        $this->setChefEtat($form->get('chef_etat'));
        $this->setJourNational($form->get('jour_national'));
        $this->setMonnaie($form->get('monnaie'));
        $this->setTaux($form->get('taux'));

        $this->setUbifranceDir($form->get('ubifrance_dir'));
        $this->setCommerceDir($form->get('chcom_dir'));

        $this->setListeNumeros($listeNumeros);
        $this->setAmbassade($ambassade);
        $this->setCampusFrance($campusFrance);
    }

    public function reset($form)
    {
        $this->setCapitale($form->get('capitale'));
        $this->setLangue($form->get('langue'));
        $this->setSuperficie(preg_replace("#\D#", "", $form->get('superficie')));
        $this->setPopulation(preg_replace("#\D#", "", $form->get('population')));
        $this->setChefEtat($form->get('chef_etat'));
        $this->setJourNational($form->get('jour_national'));
        $this->setMonnaie($form->get('monnaie'));
        $this->setTaux($form->get('taux'));

        $this->setUbifranceDir($form->get('ubifrance_dir'));
        $this->setCommerceDir($form->get('chcom_dir'));
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
     * @return Pays
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
     * Set drapeau
     *
     * @param string $drapeau
     * @return Pays
     */
    public function setDrapeau($drapeau)
    {
        $this->drapeau = $drapeau;

        return $this;
    }

    /**
     * Get drapeau
     *
     * @return string 
     */
    public function getDrapeau()
    {
        return $this->drapeau;
    }

    /**
     * Set capitale
     *
     * @param string $capitale
     * @return Pays
     */
    public function setCapitale($capitale)
    {
        $this->capitale = $capitale;

        return $this;
    }

    /**
     * Get capitale
     *
     * @return string 
     */
    public function getCapitale()
    {
        return $this->capitale;
    }

    /**
     * Set langue
     *
     * @param string $langue
     * @return Pays
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string 
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set superficie
     *
     * @param integer $superficie
     * @return Pays
     */
    public function setSuperficie($superficie)
    {
        $this->superficie = $superficie;

        return $this;
    }

    /**
     * Get superficie
     *
     * @return integer 
     */
    public function getSuperficie()
    {
        return $this->superficie;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return Pays
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return integer 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set chefEtat
     *
     * @param string $chefEtat
     * @return Pays
     */
    public function setChefEtat($chefEtat)
    {
        $this->chefEtat = $chefEtat;

        return $this;
    }

    /**
     * Get chefEtat
     *
     * @return string 
     */
    public function getChefEtat()
    {
        return $this->chefEtat;
    }

    /**
     * Set jourNational
     *
     * @param string $jourNational
     * @return Pays
     */
    public function setJourNational($jourNational)
    {
        $this->jourNational = $jourNational;

        return $this;
    }

    /**
     * Get jourNational
     *
     * @return string 
     */
    public function getJourNational()
    {
        return $this->jourNational;
    }

    /**
     * Set monnaie
     *
     * @param string $monnaie
     * @return Pays
     */
    public function setMonnaie($monnaie)
    {
        $this->monnaie = $monnaie;

        return $this;
    }

    /**
     * Get monnaie
     *
     * @return string 
     */
    public function getMonnaie()
    {
        return $this->monnaie;
    }

    /**
     * Set taux
     *
     * @param string $taux
     * @return Pays
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return string 
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set ubifranceDir
     *
     * @param string $ubifranceDir
     * @return Pays
     */
    public function setUbifranceDir($ubifranceDir)
    {
        $this->ubifranceDir = $ubifranceDir;

        return $this;
    }

    /**
     * Get ubifranceDir
     *
     * @return string 
     */
    public function getUbifranceDir()
    {
        return $this->ubifranceDir;
    }

    /**
     * Set commerceDir
     *
     * @param string $commerceDir
     * @return Pays
     */
    public function setCommerceDir($commerceDir)
    {
        $this->commerceDir = $commerceDir;

        return $this;
    }

    /**
     * Get commerceDir
     *
     * @return string 
     */
    public function getCommerceDir()
    {
        return $this->commerceDir;
    }

    /**
     * Set listeNumeros
     *
     * @param \UTravel\AdminBundle\Entity\ListeNumeros $listeNumeros
     * @return Pays
     */
    public function setListeNumeros(\UTravel\AdminBundle\Entity\ListeNumeros $listeNumeros = null)
    {
        $this->listeNumeros = $listeNumeros;

        return $this;
    }

    /**
     * Get listeNumeros
     *
     * @return \UTravel\AdminBundle\Entity\ListeNumeros 
     */
    public function getListeNumeros()
    {
        return $this->listeNumeros;
    }

    /**
     * Set ambassade
     *
     * @param \UTravel\AdminBundle\Entity\Ambassade $ambassade
     * @return Pays
     */
    public function setAmbassade(\UTravel\AdminBundle\Entity\Ambassade $ambassade = null)
    {
        $this->ambassade = $ambassade;

        return $this;
    }

    /**
     * Get ambassade
     *
     * @return \UTravel\AdminBundle\Entity\Ambassade 
     */
    public function getAmbassade()
    {
        return $this->ambassade;
    }

    /**
     * Set campusFrance
     *
     * @param \UTravel\AdminBundle\Entity\Campus $campusFrance
     * @return Pays
     */
    public function setCampusFrance(\UTravel\AdminBundle\Entity\Campus $campusFrance = null)
    {
        $this->campusFrance = $campusFrance;

        return $this;
    }

    /**
     * Get campusFrance
     *
     * @return \UTravel\AdminBundle\Entity\Campus 
     */
    public function getCampusFrance()
    {
        return $this->campusFrance;
    }
}
