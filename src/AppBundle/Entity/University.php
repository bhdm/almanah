<?php
namespace AppBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass= "AppBundle\Repository\UniversityRepository")
 * @ORM\Table(name = "university")
 */
class University 
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type = "integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity = "Country", inversedBy = "universities")
	 * @Assert\NotBlank(message = "Пожалуйста, укажите страну ВУЗа.")
	 */
	protected $country;
	
	/**
	 * @var string
	 * @ORM\Column(type = "string")
	 * @Assert\NotBlank(message = "Пожалуйста, укажите название ВУЗа.")
	 */
	protected $title;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="university")
	 */
	protected $users;

	
	public function __contsruct()
	{
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
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
		
		return $this;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function setCountry(Country $country)
	{
		$this->country = $country;
		
		return $this->country;
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