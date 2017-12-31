<?php

namespace App\Entity;

use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncomeStreamRepository")
 */
class IncomeStream
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
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
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return Budget
     */
    public function getBudget(): Budget
    {
        return $this->budget;
    }

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $frequency;

    /**
     * @ORM\ManyToOne(targetEntity="Budget", inversedBy="incomeStreams")
     * @ORM\JoinColumn(name="budget_id", referencedColumnName="id")
     * @var Budget
     */
    private $budget;

    public function __construct($name, $amount, $frequency, Budget $budget) {

        $this->name = $name;
        $this->amount = $amount;
        $this->frequency = $frequency;
        $this->budget = $budget;
    }































}
