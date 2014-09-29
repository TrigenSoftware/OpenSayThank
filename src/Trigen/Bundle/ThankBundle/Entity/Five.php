<?php

namespace Trigen\Bundle\ThankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Five
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Trigen\Bundle\ThankBundle\Entity\FiveRepository")
 */
class Five
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
     * @var integer
     *
     * @ORM\Column(name="thank_id", type="integer")
     */
    private $thankId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;


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
     * @return Five
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
     * Set thankId
     *
     * @param integer $thankId
     * @return Five
     */
    public function setThankId($thankId)
    {
        $this->thankId = $thankId;

        return $this;
    }

    /**
     * Get thankId
     *
     * @return integer 
     */
    public function getThankId()
    {
        return $this->thankId;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Five
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }
}
