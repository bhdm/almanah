<?php
namespace AppBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class StandartCategory extends Category
{
    /**
     * @ORM\OneToMany(targetEntity = "Standart", mappedBy = "category")
     */
    protected $standarts;

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
}