<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="comments")
     */
    private $owner;

    /**
     * @var Comment
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment", inversedBy="children")
     */
    private $root;

    /**
     * @var Comment
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="root")
     */
    private $children;

    /**
     * @var Publication
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Publication", inversedBy="comments")
     */
    private $publication;

    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="comments")
     */
    private $event;

    /**
     * @var Publication
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="comments")
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->children = new ArrayCollection();
    }

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Comment
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
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Comment
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param Comment $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return Comment
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Comment $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return Publication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param Publication $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return Publication
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Publication $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }



}

