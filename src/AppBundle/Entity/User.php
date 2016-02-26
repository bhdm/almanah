<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string")
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="surName", type="string", nullable=true)
     */
    protected $surName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     */
    protected $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string")
     */
    protected $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="workPlace", type="string", nullable=true)
     */
    protected $workPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="workPlaceTitle", type="string", nullable=true)
     */
    protected $workPlaceTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="workPost", type="string", nullable=true)
     */
    protected $workPost;

    /**
     * @var string
     *
     * @ORM\Column(name="workTypeOrganization", type="string", nullable=true)
     */
    protected $workTypeOrganization;

    /**
     * @var array
     *
     * @ORM\Column(name="certificateFile", type="array")
     */
    protected $certificate = array();

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="certificateDate", type="date", nullable=true)
     */
    protected $certificateDate;

    /**
     * @var string
     *
     * @ORM\Column(name="academicDegree", type="string", nullable=true)
     */
    protected $academicDegree;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\University", inversedBy="users")
     */
    protected $university;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="users")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="users")
     */
    protected $city;

    /**
     * @var Specialty
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Specialty", inversedBy="users")
     */
    protected $specialty;

    /**
     * @var RecordBook
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="user")
     */
    private $courses;

    /**
     * @var Comment
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="owner")
     */
    private $comments;


    public function __construct()
    {
        $this->certificate = array();
        $this->courses = new ArrayCollection();
        $this->comments = new ArrayCollection();
        parent::__construct();
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

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getWorkPlace()
    {
        return $this->workPlace;
    }

    /**
     * @param mixed $workPlace
     */
    public function setWorkPlace($workPlace)
    {
        $this->workPlace = $workPlace;
    }

    /**
     * @return mixed
     */
    public function getWorkPlaceTitle()
    {
        return $this->workPlaceTitle;
    }

    /**
     * @param mixed $workPlaceTitle
     */
    public function setWorkPlaceTitle($workPlaceTitle)
    {
        $this->workPlaceTitle = $workPlaceTitle;
    }

    /**
     * @return mixed
     */
    public function getWorkPost()
    {
        return $this->workPost;
    }

    /**
     * @param mixed $workPost
     */
    public function setWorkPost($workPost)
    {
        $this->workPost = $workPost;
    }

    /**
     * @return mixed
     */
    public function getWorkTypeOrganization()
    {
        return $this->workTypeOrganization;
    }

    /**
     * @param mixed $workTypeOrganization
     */
    public function setWorkTypeOrganization($workTypeOrganization)
    {
        $this->workTypeOrganization = $workTypeOrganization;
    }

    /**
     * @return mixed
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param mixed $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @return mixed
     */
    public function getCertificateDate()
    {
        return $this->certificateDate;
    }

    /**
     * @param mixed $certificateDate
     */
    public function setCertificateDate($certificateDate)
    {
        $this->certificateDate = $certificateDate;
    }

    /**
     * @return mixed
     */
    public function getAcademicDegree()
    {
        return $this->academicDegree;
    }

    /**
     * @param mixed $academicDegree
     */
    public function setAcademicDegree($academicDegree)
    {
        $this->academicDegree = $academicDegree;
    }

    /**
     * @return mixed
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * @param mixed $university
     */
    public function setUniversity($university)
    {
        $this->university = $university;
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
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Specialty
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * @param Specialty $specialty
     */
    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUsernameValue()
    {
        $this->username = $this->email;
    }

    /**
     * @return RecordBook
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param RecordBook $courses
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;
    }



}