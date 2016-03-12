<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity @ORM\Table(name="calendartype") */
class CalendarType
{
    /** @ORM\Column(type="integer") @ORM\GeneratedValue @ORM\Id */
    protected $id;

    /** @ORM\OneToMany(targetEntity="Calendar", mappedBy="type") */
    protected $calendars;

    /** @ORM\Column(length=255) */
    protected $title;

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * @param mixed $calendars
     */
    public function setCalendars($calendars)
    {
        $this->calendars = $calendars;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}