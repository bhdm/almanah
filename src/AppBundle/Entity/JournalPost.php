<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 02.11.15
 * Time: 13:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class JournalPost extends BaseEntity{

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Journal", inversedBy="posts")
     */
    protected $journal;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $titleEn;

    /**
     * @ORM\Column(type="text")
     */
    protected $descriptionEn;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywordsEn;

    /**
     * @ORM\Column(type="text")
     */
    protected $bodyEn;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pages;

    public function __construct(){
        $this->author = array();
        $this->created = new \DateTime();
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }



    /**
     * @return mixed
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param mixed $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return mixed
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param mixed $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * @return mixed
     */
    public function getBodyEn()
    {
        return $this->bodyEn;
    }

    /**
     * @param mixed $bodyEn
     */
    public function setBodyEn($bodyEn)
    {
        $this->bodyEn = $bodyEn;
    }

    /**
     * @return mixed
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @param mixed $titleEn
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getKeywordsEn()
    {
        return $this->keywordsEn;
    }

    /**
     * @param mixed $keywordsEn
     */
    public function setKeywordsEn($keywordsEn)
    {
        $this->keywordsEn = $keywordsEn;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }



}