<?php

namespace Trigen\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Trigen\Bundle\UserBundle\Entity\ProfileRepository")
 */
class Profile
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakte_photo", type="string", length=255, nullable=true)
     */
    private $vkontaktePhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakte_username", type="string", length=255, nullable=true)
     */
    private $vkontakteUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakte_realname", type="string", length=255, nullable=true)
     */
    private $vkontakteRealname;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_photo", type="string", length=255, nullable=true)
     */
    private $instagramPhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_username", type="string", length=255, nullable=true)
     */
    private $instagramUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_realname", type="string", length=255, nullable=true)
     */
    private $instagramRealname;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_photo", type="string", length=255, nullable=true)
     */
    private $twitterPhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_username", type="string", length=255, nullable=true)
     */
    private $twitterUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_realname", type="string", length=255, nullable=true)
     */
    private $twitterRealname;


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
     * Set username
     *
     * @param string $username
     * @return Profile
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Profile
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
     * Set vkontaktePhoto
     *
     * @param string $vkontaktePhoto
     * @return Profile
     */
    public function setVkontaktePhoto($vkontaktePhoto)
    {
        $this->vkontaktePhoto = $vkontaktePhoto;

        return $this;
    }

    /**
     * Get vkontaktePhoto
     *
     * @return string 
     */
    public function getVkontaktePhoto()
    {
        return $this->vkontaktePhoto;
    }

    /**
     * Set vkontakteUsername
     *
     * @param string $vkontakteUsername
     * @return Profile
     */
    public function setVkontakteUsername($vkontakteUsername)
    {
        $this->vkontakteUsername = $vkontakteUsername;

        return $this;
    }

    /**
     * Get vkontakteUsername
     *
     * @return string 
     */
    public function getVkontakteUsername()
    {
        return $this->vkontakteUsername;
    }

    /**
     * Set vkontakteRealname
     *
     * @param string $vkontakteRealname
     * @return Profile
     */
    public function setVkontakteRealname($vkontakteRealname)
    {
        $this->vkontakteRealname = $vkontakteRealname;

        return $this;
    }

    /**
     * Get vkontakteRealname
     *
     * @return string 
     */
    public function getVkontakteRealname()
    {
        return $this->vkontakteRealname;
    }

    /**
     * Set instagramPhoto
     *
     * @param string $instagramPhoto
     * @return Profile
     */
    public function setInstagramPhoto($instagramPhoto)
    {
        $this->instagramPhoto = $instagramPhoto;

        return $this;
    }

    /**
     * Get instagramPhoto
     *
     * @return string 
     */
    public function getInstagramPhoto()
    {
        return $this->instagramPhoto;
    }

    /**
     * Set instagramUsername
     *
     * @param string $instagramUsername
     * @return Profile
     */
    public function setInstagramUsername($instagramUsername)
    {
        $this->instagramUsername = $instagramUsername;

        return $this;
    }

    /**
     * Get instagramUsername
     *
     * @return string 
     */
    public function getInstagramUsername()
    {
        return $this->instagramUsername;
    }

    /**
     * Set instagramRealname
     *
     * @param string $instagramRealname
     * @return Profile
     */
    public function setInstagramRealname($instagramRealname)
    {
        $this->instagramRealname = $instagramRealname;

        return $this;
    }

    /**
     * Get instagramRealname
     *
     * @return string 
     */
    public function getInstagramRealname()
    {
        return $this->instagramRealname;
    }

    /**
     * Set twitterPhoto
     *
     * @param string $twitterPhoto
     * @return Profile
     */
    public function setTwitterPhoto($twitterPhoto)
    {
        $this->twitterPhoto = $twitterPhoto;

        return $this;
    }

    /**
     * Get twitterPhoto
     *
     * @return string 
     */
    public function getTwitterPhoto()
    {
        return $this->twitterPhoto;
    }

    /**
     * Set twitterUsername
     *
     * @param string $twitterUsername
     * @return Profile
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitterUsername = $twitterUsername;

        return $this;
    }

    /**
     * Get twitterUsername
     *
     * @return string 
     */
    public function getTwitterUsername()
    {
        return $this->twitterUsername;
    }

    /**
     * Set twitterRealname
     *
     * @param string $twitterRealname
     * @return Profile
     */
    public function setTwitterRealname($twitterRealname)
    {
        $this->twitterRealname = $twitterRealname;

        return $this;
    }

    /**
     * Get twitterRealname
     *
     * @return string 
     */
    public function getTwitterRealname()
    {
        return $this->twitterRealname;
    }
}
