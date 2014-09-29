<?php

namespace Trigen\Bundle\ThankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Trigen\Bundle\ThankBundle\Entity\RelationRepository")
 */
class Relation
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
     * @var datetime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="follows", type="string", length=255)
     */
    private $follows;


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
     * Set time
     *
     * @param string $time
     * @return Relation
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Relation
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
     * Set follows
     *
     * @param string $follows
     * @return Relation
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
}
