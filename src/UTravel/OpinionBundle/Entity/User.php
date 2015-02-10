<?php

namespace UTravel\OpinionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UTravel\OpinionBundle\Entity\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $login;
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $firstname;
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $lastname;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $mail;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $public;
    
    /**
     * @ORM\OneToMany(targetEntity="Opinion", mappedBy="author")
     */
    protected $opinions;
    /**
     * Constructor
     */
    public function __construct($session)
    {
        $this->opinions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setLogin($session->get('user_login_U7E2R'));
        $this->setFirstname($session->get('user_givenname_U7E2R'));
        $this->setLastname($session->get('user_name_U7E2R'));
        $this->setMail($session->get('user_email_U7E2R'));
        $this->setPublic(true);
    }
    
    public static function isAuthentificated ($session) {
        return $session->has('user_login_U7E2R');
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
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return User
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Add opinions
     *
     * @param \UTravel\OpinionBundle\Entity\Opinion $opinions
     * @return User
     */
    public function addOpinion(\UTravel\OpinionBundle\Entity\Opinion $opinions)
    {
        $this->opinions[] = $opinions;

        return $this;
    }

    /**
     * Remove opinions
     *
     * @param \UTravel\OpinionBundle\Entity\Opinion $opinions
     */
    public function removeOpinion(\UTravel\OpinionBundle\Entity\Opinion $opinions)
    {
        $this->opinions->removeElement($opinions);
    }

    /**
     * Get opinions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOpinions()
    {
        return $this->opinions;
    }
}
