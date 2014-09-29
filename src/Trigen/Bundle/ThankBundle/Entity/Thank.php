<?php

namespace Trigen\Bundle\ThankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Thank
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Trigen\Bundle\ThankBundle\Entity\ThankRepository")
 */
class Thank
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
     * @ORM\Column(name="thankto", type="string", length=255)
     */
    private $thankto;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="rich_body", type="string", length=255)
     */
    private $richBody;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment", type="string", length=255, nullable=true)
     */
    private $attachment;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment_url", type="string", length=255, nullable=true)
     */
    private $attachmentUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="got_fives", type="integer", nullable=true)
     */
    private $gotFives = 0;


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
     * @return Thank
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
     * @return Thank
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
     * Set thankto
     *
     * @param string $thankto
     * @return Thank
     */
    public function setThankto($thankto)
    {
        $this->thankto = $thankto;

        return $this;
    }

    /**
     * Get thankto
     *
     * @return string 
     */
    public function getThankto()
    {
        return $this->thankto;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Thank
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set richBody
     *
     * @param string $richBody
     * @return Thank
     */
    public function setRichBody($richBody)
    {
        $this->richBody = $richBody;

        return $this;
    }

    /**
     * Get richBody
     *
     * @return string 
     */
    public function getRichBody()
    {
        return $this->richBody;
    }

    /**
     * Set attachment
     *
     * @param string $attachment
     * @return Thank
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment
     *
     * @return string 
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set attachmentUrl
     *
     * @param string $attachmentUrl
     * @return Thank
     */
    public function setAttachmentUrl($attachmentUrl)
    {
        $this->attachmentUrl = $attachmentUrl;

        return $this;
    }

    /**
     * Get attachmentUrl
     *
     * @return string 
     */
    public function getAttachmentUrl()
    {
        return $this->attachmentUrl;
    }

    /**
     * Set gotFives
     *
     * @param string $gotFives
     * @return Thank
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

}
