<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organizaation
 *
 * @ORM\Table(name="organizaation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrganizaationRepository")
 */
class Organization
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="descripton", type="text")
     */
    private $descripton;

    /**
     * @var array
     *
     * @ORM\Column(name="logo", type="array", nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    public function __construct()
    {
        $this->logo = array();
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
     * @return Organizaation
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
     * Set country
     *
     * @param string $country
     *
     * @return Organizaation
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set descripton
     *
     * @param string $descripton
     *
     * @return Organizaation
     */
    public function setDescripton($descripton)
    {
        $this->descripton = $descripton;

        return $this;
    }

    /**
     * Get descripton
     *
     * @return string
     */
    public function getDescripton()
    {
        return $this->descripton;
    }

    /**
     * Set logo
     *
     * @param array $logo
     *
     * @return Organizaation
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return array
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Organizaation
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}

