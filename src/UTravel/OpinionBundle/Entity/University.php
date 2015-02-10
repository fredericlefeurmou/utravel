<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UTravel\OpinionBundle\Entity\UniversityRepository")
 * @ORM\Table(name="university")
 */
class University {
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
     * @ORM\Column(type="string", length=255)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $link;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $partnership;

    public function __toString() {
        return $this->name;
    }

    public function __construct($nom, $ville, $pays, $url)
    {
        $this->setName($nom);
        $this->setCity($ville);
        $this->setCountry($pays);
        $this->setLink($url);
        $this->setPartnership(false);
    }

    public function reset($nom, $ville, $pays, $url)
    {
        $this->setName($nom);
        $this->setCity($ville);
        $this->setCountry($pays);
        $this->setLink($url);

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
     * Set name
     *
     * @param string $name
     * @return University
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
     * Set country
     *
     * @param string $country
     * @return University
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
     * @return University
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
     * Set link
     *
     * @param string $link
     * @return University
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set partnership
     *
     * @param boolean $partnership
     * @return University
     */
    public function setPartnership($partnership)
    {
        $this->partnership = $partnership;

        return $this;
    }

    /**
     * Get partnership
     *
     * @return boolean
     */
    public function getPartnership()
    {
        return $this->partnership;
    }
}
