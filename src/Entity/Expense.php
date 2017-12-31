<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 12/27/17
 * Time: 8:12 AM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenseRepository")
 */
class Expense
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Budget
     */
    public function getBudget(): Budget
    {
        return $this->budget;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Budget", inversedBy="expenses")
     * @ORM\JoinColumn(name="budget_id", referencedColumnName="id")
     * @var Budget
     */
    private $budget;

    /**
     * Expense constructor.
     * @param $name
     * @param $amount
     * @param $budget
     */
    public function __construct($name, $amount, Budget $budget)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->budget = $budget;
    }
}