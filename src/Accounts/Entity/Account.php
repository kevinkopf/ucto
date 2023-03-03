<?php

namespace App\Accounts\Entity;

use App\Accounts\Repository\AccountRepository;
use App\Transactions\Entity\TransactionRow;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: AccountRepository::class),
    Table(name: 'accounts'),
]
class Account
{
    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[OneToMany(
        mappedBy: "account",
        targetEntity: AnalyticalAccount::class,
    )]
    private Collection $analyticals;

    #[OneToMany(
        mappedBy: "debtorsAccount",
        targetEntity: TransactionRow::class,
    )]
    private Collection $transactionRowDebtorSide;

    #[OneToMany(
        mappedBy: "creditorsAccount",
        targetEntity: TransactionRow::class,
    )]
    private Collection $transactionRowCreditorSide;

    public function __construct(
        #[Column(type: "string", length: 3)] private string $numeral,
        #[Column(type: "string")] private string $name,
        #[ManyToOne(targetEntity: AccountType::class, inversedBy: "accounts")]
        private AccountType $type,
        #[
            ManyToOne(targetEntity: AccountKind::class, inversedBy: "accounts"),
            JoinColumn(nullable: true)
        ]
        private ?AccountKind $kind = null
    )
    {
        $this->analyticals = new ArrayCollection();
        $this->transactionRowDebtorSide = new ArrayCollection();
        $this->transactionRowCreditorSide = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getNumeral(): ?string
    {
        return $this->numeral;
    }

    public function hasAnalyticals(): bool
    {
        return !$this->analyticals->isEmpty();
    }

    public function getAnalyticals(): array
    {
        return $this->analyticals->getValues();
    }

    public function addAnalytical(AnalyticalAccount $analytical): self
    {
        if (!$this->analyticals->contains($analytical))
        {
            $this->analyticals[] = $analytical;
            $analytical->setAccount($this);
        }

        return $this;
    }

    public function removeAnalytical(AnalyticalAccount $analytical): self
    {
        if ($this->analyticals->contains($analytical))
        {
            $this->analyticals->removeElement($analytical);
            // set the owning side to null (unless already changed)
            if ($analytical->getAccount() === $this)
            {
                $analytical->setAccount(null);
            }
        }

        return $this;
    }

    public function getKind(): ?AccountKind
    {
        return $this->kind;
    }

    public function setKind(?AccountKind $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getTransactionRowDebtorSide(): array
    {
        return $this->transactionRowDebtorSide->getValues();
    }

    public function addTransactionRowDebtorSide(TransactionRow $transactionRowDebtorSide): self
    {
        if (!$this->transactionRowDebtorSide->contains($transactionRowDebtorSide))
        {
            $this->transactionRowDebtorSide[] = $transactionRowDebtorSide;
            $transactionRowDebtorSide->setDebtorsAccountSecond($this);
        }

        return $this;
    }

    public function removeTransactionRowDebtorSide(TransactionRow $transactionRowDebtorSide): self
    {
        if ($this->transactionRowDebtorSide->contains($transactionRowDebtorSide))
        {
            $this->transactionRowDebtorSide->removeElement($transactionRowDebtorSide);
            // set the owning side to null (unless already changed)
            if ($transactionRowDebtorSide->getDebtorsAccountSecond() === $this)
            {
                $transactionRowDebtorSide->setDebtorsAccountSecond(null);
            }
        }

        return $this;
    }

    public function getTransactionRowCreditorSide(): array
    {
        return $this->transactionRowCreditorSide->getValues();
    }

    public function addTransactionRowCreditorSide(TransactionRow $transactionRowCreditorSide): self
    {
        if (!$this->transactionRowCreditorSide->contains($transactionRowCreditorSide))
        {
            $this->transactionRowCreditorSide[] = $transactionRowCreditorSide;
            $transactionRowCreditorSide->setCreditorsAccountSecond($this);
        }

        return $this;
    }

    public function removeTransactionRowCreditorSide(TransactionRow $transactionRowCreditorSide): self
    {
        if ($this->transactionRowCreditorSide->contains($transactionRowCreditorSide))
        {
            $this->transactionRowCreditorSide->removeElement($transactionRowCreditorSide);
            // set the owning side to null (unless already changed)
            if ($transactionRowCreditorSide->getCreditorsAccountSecond() === $this)
            {
                $transactionRowCreditorSide->setCreditorsAccountSecond(null);
            }
        }

        return $this;
    }
}
