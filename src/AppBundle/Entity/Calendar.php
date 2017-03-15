<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CalendarRepository")
 * @ORM\Table(name="calendar")
 */
class Calendar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\ManyToOne(targetEntity="CalendarType", inversedBy="calendars") */
    protected $type;

    /** @ORM\Column(length=255, nullable=true) */
    protected $firstName;

    /** @ORM\Column(length=255, nullable=true) */
    protected $lastName;

    /** @ORM\Column(length=255, nullable=true) */
    protected $surName;

    /** @ORM\Column(type="text", nullable=true) */
    protected $anons;

    /** @ORM\Column(type="text", nullable=true) */
    protected $text;

    /** @ORM\Column(length=255, nullable=true) */
    protected $title;

    /** @ORM\Column(length=255, nullable=true) */
    protected $date;

    /** @ORM\Column(length=255, nullable=true) */
    protected $birthdate;

    /** @ORM\Column(length=255, nullable=true) */
    protected $gone;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $photo;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $datetime;

    /** @ORM\Column(length=255, nullable=true) */
    protected $dayOfWeek;

    /** @ORM\Column(length=255, nullable=true) */
    protected $dayNumber;

    /** @ORM\Column(length=255, nullable=true) */
    protected $month;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $enabled;

    public function __construct()
    {
        $this->enabled = true;
    }

    public function __toString()
    {
        return '';
    }

    /**
     * @return mixed
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * @param mixed $anons
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        if (is_string($birthdate) || $birthdate == null){
            $this->birthdate = $birthdate;
        }else{
            $this->birthdate = $birthdate->format('d.m.Y');
        }

    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getGone()
    {
        return $this->gone;
    }

    /**
     * @param mixed $gone
     */
    public function setGone($gone)
    {
        if (is_string($gone) || $gone == null){
            $this->gone = $gone;
        }else{
            $this->gone = $gone->format('d.m.Y');
        }

    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param mixed $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getDayNumber()
    {
        return $this->dayNumber;
    }

    /**
     * @param mixed $dayNumber
     */
    public function setDayNumber($dayNumber)
    {
        $this->dayNumber = $dayNumber;
    }

    /**
     * @return mixed
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param mixed $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function isPerson()
    {
        return $this->birthdate || $this->gone;
    }

    public function isEvent()
    {
        return $this->type->getId() == 3;
    }

    public static function getMonths()
    {
        return array(
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря'
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}