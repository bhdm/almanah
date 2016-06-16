<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Entity()
 * @ORM\Table(name = "standart_category")
 */
class StandartCategory
{
    const
        FEED_CATEGORIES_ROOT_ID    = 1, // ID дерева категорий, которые отображаются в ленте и фильтре по типам публикаций
        // ID Категорий:
        NEWS_CATEGORY_ID           = 1,
        VIDEO_CATEGORY_ID          = 2,
        ARTICLE_CATEGORY_ID        = 3,
        COUNCIL_CATEGORY_ID        = 4,
        STUDENTCOUNCIL_CATEGORY_ID = 5,
        BLOG_CATEGORY_ID           = 6,
        DISCUSSION_CATEGORY_ID     = 7,

        // ID категорий вне лент
        MEDSOFT_CATEGORY_ID        = 13,
        PRESS_RELEASE_CATEGORY_ID  = 21,
        MEDICAL_LAW_CATEGORY_ID    = 22,
        ALL_EVENTS_ID = 15,
        COURSES_ID  = 16,
        ACTIVITY_ID = 23 ;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $seotitle;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $description;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $keywords;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $itemSeotitle;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $itemDescription;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $itemKeywords;

    /**
     * @ORM\Column(type = "string", nullable = true)
     */
    protected $h1;

    /**
     * @ORM\Column(type = "text", nullable = true)
     */
    protected $p;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @ORM\Column(type="integer")
     */
    private $root;

    /**
     * @ORM\Column(type = "string", length = 127)
     * @Assert\NotBlank(message = "Укажите название.")
     * @Assert\Length(max = 127, maxMessage = "Название не может быть длиннее 127 знаков.")
     */
    protected $title;

    /**
     * Категория показывается/не показывается гостям
     *
     * @ORM\Column(type = "boolean")
     */
    protected $public = true;

    /**
     * Категория содержит/не содержит материалы разных стран
     *
     * @ORM\Column(type = "boolean")
     */
    protected $regional = false;

    /**
     * Категория показывается/не показывается в главной ленте
     *
     * @ORM\Column(type = "boolean")
     */
    protected $inMainFeed = false;

    /**
     * Категория показывается/не показывается ленте тематических групп и компаний
     *
     * @ORM\Column(type = "boolean")
     */
    protected $inGroupFeed = false;

    /**
     * Категория показывается/не показывается в ленте на странице пользователя
     *
     * @ORM\Column(type = "boolean")
     */
    protected $inUserFeed = false;

    /**
     * Если false - у публикаций данной категории скрывается preview-картинка при просмотре полного текста публикации (например, в категории видео)
     *
     * @ORM\Column(type = "boolean")
     */
    protected $alwaysShowPreview = true;

    /**
     * @ORM\OneToMany(targetEntity = "Standart", mappedBy = "category")
     */
    protected $standarts;

    /**
     * @ORM\Column(type = "string", nullable=true)
     */
    protected $categoryType;

    public function __toString()
    {
        return ''.$this->title;
    }

    public function getId() { return $this->id; }

    public function isAlwaysShowPreview() { return $this->alwaysShowPreview; }
    public function setAlwaysShowPreview($alwaysShowPreview) { $this->alwaysShowPreview = $alwaysShowPreview; }

    public function getLeftValue() { return $this->lft; }
    public function setLeftValue($lft) { $this->lft = $lft; }

    public function getRightValue() { return $this->rgt; }
    public function setRightValue($rgt) { $this->rgt = $rgt; }

    public function getRootValue() { return $this->root; }
    public function setRootValue($root) { $this->root = $root; }

    public function setTitle($title) { $this->title = $title; return $this; }
    public function getTitle() { return $this->title; }

    public function getSeotitle() { return $this->seotitle; }

    public function getDescription() { return $this->description; }

    public function getKeywords() { return $this->keywords; }

    public function getItemDescription() { return $this->itemDescription; }

    public function getItemSeotitle() { return $this->itemSeotitle; }

    public function getItemKeywords() { return $this->itemKeywords; }

    public function getH1() { return $this->h1; }

    public function getP() { return $this->p; }

    public function isPublic() { return $this->public; }

    public function isRegional() { return $this->regional; }

    public function isInMainFeed() { return $this->inMainFeed; }

    public function isInGroupFeed() { return $this->inGroupFeed; }

    public function isInUserFeed() { return $this->inUserFeed; }

    public function __construct()
    {
        $this->standart = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getStandarts()
    {
        return $this->standarts;
    }


    public function getType()
    {
        return 'standart';
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return ArrayCollection
     */
    public function getStandart()
    {
        return $this->standart;
    }

    /**
     * @param ArrayCollection $standart
     */
    public function setStandart($standart)
    {
        $this->standart = $standart;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return mixed
     */
    public function getRegional()
    {
        return $this->regional;
    }

    /**
     * @param mixed $regional
     */
    public function setRegional($regional)
    {
        $this->regional = $regional;
    }

    /**
     * @return mixed
     */
    public function getInMainFeed()
    {
        return $this->inMainFeed;
    }

    /**
     * @param mixed $inMainFeed
     */
    public function setInMainFeed($inMainFeed)
    {
        $this->inMainFeed = $inMainFeed;
    }

    /**
     * @return mixed
     */
    public function getInGroupFeed()
    {
        return $this->inGroupFeed;
    }

    /**
     * @param mixed $inGroupFeed
     */
    public function setInGroupFeed($inGroupFeed)
    {
        $this->inGroupFeed = $inGroupFeed;
    }

    /**
     * @return mixed
     */
    public function getInUserFeed()
    {
        return $this->inUserFeed;
    }

    /**
     * @param mixed $inUserFeed
     */
    public function setInUserFeed($inUserFeed)
    {
        $this->inUserFeed = $inUserFeed;
    }

    /**
     * @return mixed
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @param mixed $categoryType
     */
    public function setCategoryType($categoryType)
    {
        $this->categoryType = $categoryType;
    }

    
}