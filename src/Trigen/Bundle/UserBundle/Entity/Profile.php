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
     * @var integer
     *
     * @ORM\Column(name="follows", type="integer", nullable=true)
     */
    private $follows = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="followers", type="integer", nullable=true)
     */
    private $followers = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="said_thanks", type="integer", nullable=true)
     */
    private $saidThanks = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="got_thanks", type="integer", nullable=true)
     */
    private $gotThanks = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="gave_fives", type="integer", nullable=true)
     */
    private $gaveFives = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="got_fives", type="integer", nullable=true)
     */
    private $gotFives = 0;

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
     * Set follows
     *
     * @param string $follows
     * @return Profile
     */
    public function setFollows($follows)
    {
        $this->follows = $follows;

        return $this;
    }

    /**
     * Get follows
     *
     * @return string 
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * Set followers
     *
     * @param string $followers
     * @return Profile
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;

        return $this;
    }

    /**
     * Get followers
     *
     * @return string 
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set saidThanks
     *
     * @param string $saidThanks
     * @return Profile
     */
    public function setSaidThanks($saidThanks)
    {
        $this->saidThanks = $saidThanks;

        return $this;
    }

    /**
     * Get saidThanks
     *
     * @return string 
     */
    public function getSaidThanks()
    {
        return (int)$this->saidThanks;
    }

    /**
     * Set gotThanks
     *
     * @param string $gotThanks
     * @return Profile
     */
    public function setGotThanks($gotThanks)
    {
        $this->gotThanks = $gotThanks;

        return $this;
    }

    /**
     * Get gotThanks
     *
     * @return string 
     */
    public function getGotThanks()
    {
        return (int)$this->gotThanks;
    }

    /**
     * Set gaveFives
     *
     * @param string $gaveFives
     * @return Profile
     */
    public function setGaveFives($gaveFives)
    {
        $this->gaveFives = $gaveFives;

        return $this;
    }

    /**
     * Get gaveFives
     *
     * @return string 
     */
    public function getGaveFives()
    {
        return (int)$this->gaveFives;
    }

    /**
     * Set gotFives
     *
     * @param string $gotFives
     * @return Profile
     */
    public function setGotFives($gotFives)
    {
        $this->gotFives = $gotFives;

        return $this;
    }

    /**
     * Get gotFives
     *
     * @return string 
     */
    public function getGotFives()
    {
        return (int)$this->gotFives;
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
