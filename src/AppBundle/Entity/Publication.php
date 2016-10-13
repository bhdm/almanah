<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PublicationRepository")
 */
class Publication
{

    const PUBLICATIONS = 0;
    const NEWS = 1;
    const CLINIC = 2;

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
     * @ORM\Column(name="source", type="string", nullable=true)
     */
    private $source;


    /**
     * @var string
     *
     * @ORM\Column(name="anons", type="text", nullable=true)
     */
    private $anons;


    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var array
     *
     * @ORM\Column(name="preview", type="array")
     */
    private $preview;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="publications")
     */
    private $category;

    /**
     * @var Specialty
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Specialty", inversedBy="publications")
     */
    private $specialties;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_digest", type="boolean", nullable=true)
     */
    private $digest;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="metaKeyword", type="string", length=255, nullable=true)
     */
    private $metaKeyword;

    /**
     * @var Comment
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="publication")
     */
    private $comments;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $public;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var Comment
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="publications")
     */
    private $author;

    /**
     * @ORM\Column(name="slike", type="integer")
     */
    private $like;

    /**
     * @ORM\Column(name="sdislike", type="integer")
     */
    private $dislike;

    /**
     * @ORM\Column(type="integer")
     */
    private $statShow;


    public function __construct()
    {
        $this->enabled = true;
        $this->created = new \DateTime();
        $this->preview = array();
        $this->specialties = new ArrayCollection();
        $this->digest = false;
        $this->public = false;
        $this->comments = new ArrayCollection();
        $this->type = self::NEWS;

        $this->like = 0;
        $this->dislike = 0;
        $this->statShow = 0;
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
     * @return Publication
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
     * Set body
     *
     * @param string $body
     *
     * @return Publication
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
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled = true)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param array $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * @return Specialty
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * @param Specialty $specialties
     */
    public function setSpecialties($specialties)
    {
        $this->specialties = $specialties;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * @param string $anons
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    }

    /**
     * @return boolean
     */
    public function isDigest()
    {
        return $this->digest;
    }

    /**
     * @param boolean $digest
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaKeyword()
    {
        return $this->metaKeyword;
    }

    /**
     * @param string $metaKeyword
     */
    public function setMetaKeyword($metaKeyword)
    {
        $this->metaKeyword = $metaKeyword;
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

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Comment
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Comment $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getTypeStr(){
        if ($this->type == 0){
            return 'Статья';
        }
        if ($this->type == 1){
            return 'Новость';
        }
        if ($this->type == 2){
            return 'Исследование';
        }
    }

    /**
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @param mixed $like
     */
    public function setLike($like)
    {
        $this->like = $like;
    }

    /**
     * @return mixed
     */
    public function getDislike()
    {
        return $this->dislike;
    }

    /**
     * @param mixed $dislike
     */
    public function setDislike($dislike)
    {
        $this->dislike = $dislike;
    }

    /**
     * @return int
     */
    public function getStatisticShow()
    {
        return $this->statisticShow;
    }

    /**
     * @param int $statisticShow
     */
    public function setStatisticShow($statisticShow)
    {
        $this->statisticShow = $statisticShow;
    }

    /**
     * @return mixed
     */
    public function getShow()
    {
        return $this->statShow;
    }

    /**
     * @param mixed $statShow
     */
    public function setShow($statShow)
    {
        $this->statShow = $statShow;
    }

}

