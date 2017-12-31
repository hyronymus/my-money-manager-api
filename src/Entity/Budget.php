<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 12/27/17
 * Time: 8:09 AM
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BudgetRepository")
 */
class Budget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * One Budget has Many Income Streams.
     * @ORM\OneToMany(targetEntity="IncomeStream", mappedBy="budget", cascade={"persist"})
     *
     * @var array
     */
    private $incomeStreams;

    /**
     * @return IncomeStream[]
     */
    public function getIncomeStreams(): array
    {
        return $this->incomeStreams->toArray();
    }

    /**
     * @return Expense[]
     */
    public function getExpenses(): array
    {
        return $this->expenses->toArray();
    }

    public function getId(): Int
    {
        return $this->id;
    }

    /**
     * One Budget has Many Expenses.
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="budget", cascade={"persist"})
     *
     * @var array
     */
    private $expenses;

    public function __construct()
    {
        $this->incomeStreams = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function setIncomeStreams(array $incomeStreams)
    {
        $this->incomeStreams = new ArrayCollection($incomeStreams);
    }

    public function setExpenses(array $expenses)
    {
        $this->expenses = new ArrayCollection($expenses);
    }

}