<?php

namespace UTravel\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WelcomeWidget
 *
 * @ORM\Table(name="widget")
 * @ORM\Entity
 */
class WelcomeWidget
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
     * @var boolean
     *
     * @ORM\Column(name="is_video", type="boolean")
     */
    private $isVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="iframe_link", type="string", length=511, nullable=true)
     */
    private $iframeLink;


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
     * Set isVideo
     *
     * @param boolean $isVideo
     * @return WelcomeWidget
     */
    public function setIsVideo($isVideo)
    {
        $this->isVideo = $isVideo;

        return $this;
    }

    /**
     * Get isVideo
     *
     * @return boolean 
     */
    public function getIsVideo()
    {
        return $this->isVideo;
    }

    /**
     * Set iframeLink
     *
     * @param string $iframeLink
     * @return WelcomeWidget
     */
    public function setIframeLink($iframeLink)
    {
        $this->iframeLink = $iframeLink;

        return $this;
    }

    /**
     * Get iframeLink
     *
     * @return string 
     */
    public function getIframeLink()
    {
        return $this->iframeLink;
    }
}
