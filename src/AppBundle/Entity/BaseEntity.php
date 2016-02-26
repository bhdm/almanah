<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 02.11.15
 * Time: 13:34
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class BaseEntity
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled = 1;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->enabled = true;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled = 1)
    {
        $this->enabled = $enabled;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}