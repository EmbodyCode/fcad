<?php

namespace FcadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="FcadBundle\Repository\FeedbackRepository")
 */
class Feedback
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sendername", type="string", length=255)
     */
    private $sendername;

    /**
     * @var string
     *
     * @ORM\Column(name="senderemail", type="string", length=255)
     */
    private $senderemail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent", type="datetime")
     */
    private $sent;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=2000)
     */
    private $text;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sendername
     *
     * @param string $sendername
     *
     * @return Feedback
     */
    public function setSendername($sendername)
    {
        $this->sendername = $sendername;

        return $this;
    }

    /**
     * Get sendername
     *
     * @return string
     */
    public function getSendername()
    {
        return $this->sendername;
    }

    /**
     * Set senderemail
     *
     * @param string $senderemail
     *
     * @return Feedback
     */
    public function setSenderemail($senderemail)
    {
        $this->senderemail = $senderemail;

        return $this;
    }

    /**
     * Get senderemail
     *
     * @return string
     */
    public function getSenderemail()
    {
        return $this->senderemail;
    }

    /**
     * Set sent
     *
     * @param \DateTime $sent
     *
     * @return Feedback
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Feedback
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}

