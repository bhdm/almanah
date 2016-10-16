<?php
namespace AppBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 * @ORM\Table(name = "geoCity")
 */
class City
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type = "integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity = "Country", inversedBy = "cities")
	 * @Assert\NotBlank(message = "Пожалуйста, укажите страну.")
	 */
	protected $country;

	/**
	 * @var string
	 * @ORM\Column(type = "string")
	 * @Assert\NotBlank(message = "Пожалуйста, укажите название города.")
	 * @Assert\Length(max = 63, maxMessage = "Название города не может быть длиннее {{limit}}.")
	 */
	protected $title;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="city")
	 */
	private $events;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="city")
	 */
	private $users;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $x;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $y;

	public function __construct()
	{
		$this->events = new ArrayCollection();
		$this->users = new ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->title;
	}

	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * @param ArrayCollection $events
	 */
	public function setEvents($events)
	{
		$this->events = $events;
	}

	/**
	 * @return mixed
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @param mixed $users
	 */
	public function setUsers($users)
	{
		$this->users = $users;
	}

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }




}