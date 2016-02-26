<?php
namespace AppBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name = "geoCountry")
 */
class Country
{
	const RUSSIA_COUNTRY_ID = 1;

	/**
	 * @ORM\Id
	 * @ORM\Column(type = "integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\OneToMany(targetEntity = "City", mappedBy = "country")
	 */
	protected $cities;

	/** @ORM\OneToMany(targetEntity = "University", mappedBy = "country") */
	protected $universities;

	/** @ORM\OneToMany(targetEntity = "Publication", mappedBy = "country") */
	protected $publications;

	/**
	 * @var string
	 * @ORM\Column(type = "string", length = 63)
	 * @Assert\NotBlank(message = "Укажите название страны.")
	 * @Assert\Length(max = 63, maxMessage = "Название страны должно быть не длиннее 63 знаков.")
	 */
	protected $title;

	/**
	 * @ORM\Column(name="shortTitle", type = "string", length = 4, nullable = true)
	 * @Assert\Length(max = 4, maxMessage = "Сокращенное название страны должно быть не длиннее 4 знаков.")
	 */
	protected $shortTitle;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="country")
	 */
	protected $users;


	public function __construct()
	{
		$this->universities = new ArrayCollection();
		$this->cities       = new ArrayCollection();
		$this->users       = new ArrayCollection();
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

	public function getCities()
	{
		return $this->cities;
	}

	public function setCities(ArrayCollection $cities)
	{
		$this->cities = $cities;

		return $this;
	}

	public function getUniversities()
	{
		return $this->universities;
	}

	public function setUniversities(ArrayCollection $universities)
	{
		$this->universities = $universities;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getShortTitle()
	{
		return $this->shortTitle;
	}

	public function setShortTitle($shortTitle)
	{
		$this->shortTitle = $shortTitle;

		return $this;
	}

    /**
     * @param mixed $banners
     */
    public function setBanners($banners)
    {
        $this->banners = $banners;
    }

    /**
     * @return mixed
     */
    public function getBanners()
    {
        return $this->banners;
    }

    /**
     * @param mixed $bannersRotate
     */
    public function setBannersRotate($bannersRotate)
    {
        $this->bannersRotate = $bannersRotate;
    }

    /**
     * @return mixed
     */
    public function getBannersRotate()
    {
        return $this->bannersRotate;
    }

    /**
     * @param mixed $publications
     */
    public function setPublications($publications)
    {
        $this->publications = $publications;
    }

    /**
     * @return mixed
     */
    public function getPublications()
    {
        return $this->publications;
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



}