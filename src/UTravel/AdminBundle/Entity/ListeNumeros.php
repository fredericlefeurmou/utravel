<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListeNumeros
 *
 * @ORM\Table("numeros_utiles")
 * @ORM\Entity
 */
class ListeNumeros
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
     * @ORM\Column(name="police", type="string", length=255)
     */
    private $police;

    /**
     * @var string
     *
     * @ORM\Column(name="samu", type="string", length=255)
     */
    private $samu;

    /**
     * @var string
     *
     * @ORM\Column(name="pompiers", type="string", length=255)
     */
    private $pompiers;

    public function __construct($form)
    {
        $this->setPolice(preg_replace("#\D#", "", $form->get('num_police')));
        $this->setSamu(preg_replace("#\D#", "", $form->get('num_samu')));
        $this->setPompiers(preg_replace("#\D#", "", $form->get('num_pompiers')));
    }

    public function reset($form)
    {
        $this->setPolice(preg_replace("#\D#", "", $form->get('num_police')));
        $this->setSamu(preg_replace("#\D#", "", $form->get('num_samu')));
        $this->setPompiers(preg_replace("#\D#", "", $form->get('num_pompiers')));

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
     * Set police
     *
     * @param string $police
     * @return ListeNumeros
     */
    public function setPolice($police)
    {
        $this->police = $police;

        return $this;
    }

    /**
     * Get police
     *
     * @return string 
     */
    public function getPolice()
    {
        return $this->police;
    }

    /**
     * Set samu
     *
     * @param string $samu
     * @return ListeNumeros
     */
    public function setSamu($samu)
    {
        $this->samu = $samu;

        return $this;
    }

    /**
     * Get samu
     *
     * @return string 
     */
    public function getSamu()
    {
        return $this->samu;
    }

    /**
     * Set pompiers
     *
     * @param string $pompiers
     * @return ListeNumeros
     */
    public function setPompiers($pompiers)
    {
        $this->pompiers = $pompiers;

        return $this;
    }

    /**
     * Get pompiers
     *
     * @return string 
     */
    public function getPompiers()
    {
        return $this->pompiers;
    }
}
