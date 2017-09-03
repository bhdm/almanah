<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventPage
 *
 * @ORM\Table(name="event_page")
 * @ORM\Entity()
 */
class EventPage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 0: Страница в меню
     * 1: Просто страница
     * 2: Попап
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="menu", type="string", length=255, nullable=true)
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="ord", type="integer")
     */
    private $ord;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="pages")
     */
    private $event;

    /**
     * 0: Нет формы
     * 1: Форма вопроса
     * 2: Форма подписки
     * @ORM\Column(type="integer")
     */
    private $form;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $formEmail;


    public function __toString()
    {
        return $this->title.' ';
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
     * @return EventPage
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
     * Set menu
     *
     * @param string $menu
     *
     * @return EventPage
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return EventPage
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set ord
     *
     * @param integer $ord
     *
     * @return EventPage
     */
    public function setOrd($ord)
    {
        $this->ord = $ord;

        return $this;
    }

    /**
     * Get ord
     *
     * @return int
     */
    public function getOrd()
    {
        return $this->ord;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return EventPage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getFormEmail()
    {
        return $this->formEmail;
    }

    /**
     * @param mixed $formEmail
     */
    public function setFormEmail($formEmail)
    {
        $this->formEmail = $formEmail;
    }



}

