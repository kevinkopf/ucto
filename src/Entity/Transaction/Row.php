<?php

namespace App\Entity\Transaction;

use App\Entity\Account;
use App\Entity\Account\Analytical;
use App\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups("transactions")
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @Groups("transactions")
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="transactionRowDebtorSide")
     * @ORM\JoinColumn(nullable=false)
     */
    private $debtorsAccount;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="transactionRowCreditorSide")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditorsAccount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Analytical")
     */
    private $debtorsAccountAnalytical;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Analytical")
     */
    private $creditorsAccountAnalytical;

    /**
     * Row constructor.
     * @param ?string $description
     * @param Account $debtorsAccount
     * @param Account $creditorsAccount
     * @param int $amount
     * @param Analytical|null $debtorsAccountAnalytical
     * @param Analytical|null $creditorsAccountAnalytical
     * @return self
     */
    public function hydrate(
        ?string $description,
        Account $debtorsAccount,
        Account $creditorsAccount,
        int $amount,
        Analytical $debtorsAccountAnalytical = null,
        Analytical $creditorsAccountAnalytical = null
    ): self
    {
        $this->description = $description;
        $this->debtorsAccount = $debtorsAccount;
        $this->creditorsAccount = $creditorsAccount;
        $this->amount = $amount;
        $this->debtorsAccountAnalytical = $debtorsAccountAnalytical;
        $this->creditorsAccountAnalytical = $creditorsAccountAnalytical;

        return $this;
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

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getDebtorsAccount(): ?Account
    {
        return $this->debtorsAccount;
    }

    /**
     * @param Account|null $debtorsAccount
     * @return $this
     */
    public function setDebtorsAccount(?Account $debtorsAccount): self
    {
        $this->debtorsAccount = $debtorsAccount;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getCreditorsAccount(): ?Account
    {
        return $this->creditorsAccount;
    }

    /**
     * @param Account|null $creditorsAccount
     * @return $this
     */
    public function setCreditorsAccount(?Account $creditorsAccount): self
    {
        $this->creditorsAccount = $creditorsAccount;

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
}
