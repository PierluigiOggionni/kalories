<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meal
 *
 * @ORM\Table(name="meal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\MealRepository")
 */
class Meal
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
     * @var \DateTime
     *
     * @ORM\Column(name="meal_date", type="date")
     */
    private $mealDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="meal_date_time", type="datetime")
     */
    private $mealDateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */

    private $user;

    /**
     * @ORM\Column(name="calories", type="integer")
     *
     */

    private $calories=10;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mealDate
     *
     * @param \DateTime $mealDate
     * @return Meal
     */
    public function setMealDate($mealDate)
    {
        $this->mealDate = $mealDate;

        return $this;
    }

    /**
     * Get mealDate
     *
     * @return \DateTime 
     */
    public function getMealDate()
    {
        return $this->mealDate;
    }

    /**
     * Set mealDateTime
     *
     * @param \DateTime $mealDateTime
     * @return Meal
     */
    public function setMealDateTime($mealDateTime)
    {
        $this->mealDateTime = $mealDateTime;

        return $this;
    }

    /**
     * Get mealDateTime
     *
     * @return \DateTime 
     */
    public function getMealDateTime()
    {
        return $this->mealDateTime;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Meal
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * @param mixed $calories
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;
    }




}
