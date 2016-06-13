<?php
namespace AppBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\StandartRepository")
 * @ORM\Table(name = "standart")
 */
class Standart extends BaseEntity
{
    /**
     * @ORM\Column(type = "string", length = 255)
     * @Assert\NotBlank(message = "Заполните название.")
     * @Assert\Length(max = 255, maxMessage = "Название не может быть длиннее {{ limit }} знаков")
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity = "StandartCategory", inversedBy = "standarts")
     * @Assert\NotBlank(message = "Пожалуйста, укажите тип события.")
     */
    protected $category;

    /**
     * @ORM\Column(type = "text", nullable = true)
     */
    protected $body;

    /**
     * @ORM\Column(type = "datetime", nullable = true)
     */
    protected $updated;

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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
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
    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return empty($this->title) ? '' : $this->title;
    }
}
