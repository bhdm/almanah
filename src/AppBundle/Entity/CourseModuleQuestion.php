<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CourseModuleQuestion
 *
 * @ORM\Table(name="course_module_question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseModuleQuestionRepository")
 */
class CourseModuleQuestion
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
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var CourseModule
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CourseModule", inversedBy="questions")
     */
    private $module;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CourseModuleAnswer", mappedBy="question", cascade={"remove"}, orphanRemoval=true)
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * Set body
     *
     * @param string $body
     *
     * @return CourseModuleQuestion
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
     * Set amountTime
     *
     * @param integer $amountTime
     *
     * @return CourseModuleQuestion
     */
    public function setAmountTime($amountTime)
    {
        $this->amountTime = $amountTime;

        return $this;
    }

    /**
     * Get amountTime
     *
     * @return int
     */
    public function getAmountTime()
    {
        return $this->amountTime;
    }

    /**
     * Set allowError
     *
     * @param integer $allowError
     *
     * @return CourseModuleQuestion
     */
    public function setAllowError($allowError)
    {
        $this->allowError = $allowError;

        return $this;
    }

    /**
     * Get allowError
     *
     * @return int
     */
    public function getAllowError()
    {
        return $this->allowError;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }
}

