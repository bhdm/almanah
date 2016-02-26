<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseRepository")
 */
class Course
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var array
     *
     * @ORM\Column(name="image", type="array", nullable=true)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="amountHour", type="integer", nullable=true)
     */
    private $amountHour;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CourseModule", mappedBy="course", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"sort" = "ASC", "id" = "ASC" })
     */
    private $modules;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Specialty", inversedBy="courses")
     */
    private $specialties;

    /**
     * @var Comment
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="course", cascade={"remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $comments;

    /**
     * @var RecordBook
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RecordBook", mappedBy="course", cascade={"remove"}, orphanRemoval=true)
     */
    private $users;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->enabled = false;
        $this->price = 0;
        $this->created  = new \DateTime();
        $this->modules = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->image = array();
        $this->specialties = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     *
     * @return Course
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Course
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Course
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
     * Set image
     *
     * @param array $image
     *
     * @return Course
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return array
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Course
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set amountHour
     *
     * @param integer $amountHour
     *
     * @return Course
     */
    public function setAmountHour($amountHour)
    {
        $this->amountHour = $amountHour;

        return $this;
    }

    /**
     * Get amountHour
     *
     * @return int
     */
    public function getAmountHour()
    {
        return $this->amountHour;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Course
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Course
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
     * Set start
     *
     * @param string $start
     *
     * @return Course
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return ArrayCollection
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param ArrayCollection $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return Course
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Course $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * @param ArrayCollection $specialties
     */
    public function setSpecialties($specialties)
    {
        $this->specialties = $specialties;
    }

    /**
     * @return Comment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }




}

