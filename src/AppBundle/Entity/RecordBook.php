<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecordBook
 *
 * @ORM\Table(name="record_book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordBookRepository")
 */
class RecordBook
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
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="passed", type="datetime", nullable=true)
     */
    private $passed;

    /**
     * @var string
     *
     * @ORM\Column(name="percent", type="decimal", scale=2, nullable=true)
     */
    private $percent;

    /**
     * @var CourseModule
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CourseModule")
     */
    private $activeModule;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="courses")
     */
    private $user;

    /**
     * @var Course
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="users")
     */
    private $course;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->enabled = true;
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
     * @return RecordBook
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return RecordBook
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
     * Set passed
     *
     * @param \DateTime $passed
     *
     * @return RecordBook
     */
    public function setPassed($passed)
    {
        $this->passed = $passed;

        return $this;
    }

    /**
     * Get passed
     *
     * @return \DateTime
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * Set percent
     *
     * @param string $percent
     *
     * @return RecordBook
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return string
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set activeModule
     *
     * @param integer $activeModule
     *
     * @return RecordBook
     */
    public function setActiveModule($activeModule)
    {
        $this->activeModule = $activeModule;

        return $this;
    }

    /**
     * Get activeModule
     *
     * @return int
     */
    public function getActiveModule()
    {
        return $this->activeModule;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }



}

