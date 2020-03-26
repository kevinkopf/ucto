<?php

namespace App\Entity\Transaction;

use App\Entity\Account;
use App\Entity\Account\Analytical;
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $debtorsAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\Analytical", cascade={"persist", "remove"})
     */
    private $debtorsAccountAnalytical;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditorsAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\Analytical", cascade={"persist", "remove"})
     */
    private $creditorsAccountAnalytical;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * Row constructor.
     * @param string $description
     * @param Account $debtorsAccount
     * @param Account $creditorsAccount
     * @param int $amount
     * @param Analytical|null $debtorsAccountAnalytical
     * @param Analytical|null $creditorsAccountAnalytical
     */
    public function __construct(
        ?string $description,
        Account $debtorsAccount,
        Account $creditorsAccount,
        int $amount,
        Analytical $debtorsAccountAnalytical = null,
        Analytical $creditorsAccountAnalytical = null
    ) {
        $this->description = $description;
        $this->debtorsAccount = $debtorsAccount;
        $this->creditorsAccount = $creditorsAccount;
        $this->amount = $amount;
        $this->debtorsAccountAnalytical = $debtorsAccountAnalytical;
        $this->creditorsAccountAnalytical = $creditorsAccountAnalytical;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getDebtorsAccount(): Account
    {
        return $this->debtorsAccount;
    }

    /**
     * @param Account $debtorsAccount
     * @return $this
     */
    public function setDebtorsAccount(Account $debtorsAccount): self
    {
        $this->debtorsAccount = $debtorsAccount;

        return $this;
    }

    /**
     * @return Analytical|null
     */
    public function getDebtorsAccountAnalytical(): ?Analytical
    {
        return $this->debtorsAccountAnalytical;
    }

    /**
     * @param Analytical|null $debtorsAccountAnalytical
     * @return $this
     */
    public function setDebtorsAccountAnalytical(?Analytical $debtorsAccountAnalytical): self
    {
        $this->debtorsAccountAnalytical = $debtorsAccountAnalytical;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getCreditorsAccount(): Account
    {
        return $this->creditorsAccount;
    }

    /**
     * @param Account $creditorsAccount
     * @return $this
     */
    public function setCreditorsAccount(Account $creditorsAccount): self
    {
        $this->creditorsAccount = $creditorsAccount;

        return $this;
    }

    /**
     * @return Analytical|null
     */
    public function getCreditorsAccountAnalytical(): ?Analytical
    {
        return $this->creditorsAccountAnalytical;
    }

    /**
     * @param Analytical|null $creditorsAccountAnalytical
     * @return $this
     */
    public function setCreditorsAccountAnalytical(?Analytical $creditorsAccountAnalytical): self
    {
        $this->creditorsAccountAnalytical = $creditorsAccountAnalytical;

        return $this;
    }

    /**
     * @return Transaction|null
     */
    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    /**
     * @param Transaction|null $transaction
     * @return $this
     */
    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
