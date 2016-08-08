<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Entity @ORM\Table(name="email") */
class Email
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    protected $id;

    /**
     * @ORM\Column(type = "string", unique = true)
     * @Assert\NotBlank(message = "Введите e-mail")
     * @Assert\Email(message = "Некорректный e-mail")
     */
    protected $email;

    /**
     * @ORM\Column(type = "boolean")
     */
    protected $sent = false;

    /** @ORM\Column(type = "boolean") */
    protected $ru = false;

    public function __toString()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $sent
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    /**
     * @return mixed
     */
    public function getSent()
    {
        return $this->sent;
    }
}