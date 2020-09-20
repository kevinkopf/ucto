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
    private ?int $id;

    /**
     * @Groups("transactions")
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private Transaction $transaction;

    /**
     * @Groups("transactions")
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="transactionRowDebtorSide")
     * @ORM\JoinColumn(nullable=false)
     */
    private Account $debtorsAccount;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="transactionRowCreditorSide")
     * @ORM\JoinColumn(nullable=false)
     */
    private Account $creditorsAccount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Analytical")
     */
    private ?Analytical $debtorsAccountAnalytical;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Analytical")
     */
    private ?Analytical $creditorsAccountAnalytical;

    public function __construct(
        string $description,
        Account $debtorsAccount,
        Account $creditorsAccount,
        int $amount,
        Analytical $debtorsAccountAnalytical = null,
        Analytical $creditorsAccountAnalytical = null
    ) {
        $this->update(
            $description,
            $debtorsAccount,
            $creditorsAccount,
            $amount,
            $debtorsAccountAnalytical,
            $creditorsAccountAnalytical
        );
    }

    public function update(
        string $description,
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
     * @return Account|null
     */
    public function getDebtorsAccount(): ?Account
    {
        return $this->debtorsAccount;
    }

    /**
     * @return Account|null
     */
    public function getCreditorsAccount(): ?Account
    {
        return $this->creditorsAccount;
    }

    /**
     * @return Analytical|null
     */
    public function getDebtorsAccountAnalytical(): ?Analytical
    {
        return $this->debtorsAccountAnalytical;
    }

    /**
     * @return Analytical|null
     */
    public function getCreditorsAccountAnalytical(): ?Analytical
    {
        return $this->creditorsAccountAnalytical;
    }
}
