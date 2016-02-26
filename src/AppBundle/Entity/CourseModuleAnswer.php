<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourseModuleAnswer
 *
 * @ORM\Table(name="course_module_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseModuleAnswerRepository")
 */
class CourseModuleAnswer
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
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(name="correct", type="boolean", nullable=true)
     */
    private $correct;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @var CourseModuleQuestion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CourseModuleQuestion", inversedBy="answers")
     */
    private $question;


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
     * @return CourseModuleAnswer
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
     * Set correct
     *
     * @param boolean $correct
     *
     * @return CourseModuleAnswer
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return bool
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return CourseModuleAnswer
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return CourseModuleQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param CourseModuleQuestion $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }


}

