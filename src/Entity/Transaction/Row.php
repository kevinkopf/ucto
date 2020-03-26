<?php

namespace App\Entity\Transaction;

use App\Entity\Account;
use App\Entity\Account\Analysis;
use App\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\RowRepository")
 * @ORM\Table(name="transactions_rows")
 */
class Row
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $debtorsAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\Analysis", cascade={"persist", "remove"})
     */
    private $debtorsAccountAnalytical;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditorsAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\Analysis", cascade={"persist", "remove"})
     */
    private $creditorsAccountAnalytical;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDebtorsAccount(): ?Account
    {
        return $this->debtorsAccount;
    }

    public function setDebtorsAccount(Account $debtorsAccount): self
    {
        $this->debtorsAccount = $debtorsAccount;

        return $this;
    }

    public function getDebtorsAccountAnalytical(): ?Analysis
    {
        return $this->debtorsAccountAnalytical;
    }

    public function setDebtorsAccountAnalytical(?Analysis $debtorsAccountAnalytical): self
    {
        $this->debtorsAccountAnalytical = $debtorsAccountAnalytical;

        return $this;
    }

    public function getCreditorsAccount(): ?Account
    {
        return $this->creditorsAccount;
    }

    public function setCreditorsAccount(Account $creditorsAccount): self
    {
        $this->creditorsAccount = $creditorsAccount;

        return $this;
    }

    public function getCreditorsAccountAnalytical(): ?Analysis
    {
        return $this->creditorsAccountAnalytical;
    }

    public function setCreditorsAccountAnalytical(?Analysis $creditorsAccountAnalytical): self
    {
        $this->creditorsAccountAnalytical = $creditorsAccountAnalytical;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }
}
