<?php

declare(strict_types=1);

namespace App\Transactions\Entity;

use App\Accounts\Entity\Account;
use App\Accounts\Entity\AnalyticalAccount;
use App\Transactions\Repository\TransactionRowRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: TransactionRowRepository::class),
    Table(name: 'transactions_rows'),
]
class TransactionRow
{
    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[
        ManyToOne(targetEntity: Transaction::class, fetch: "EAGER", inversedBy: "rows"),
        JoinColumn(nullable: false)
    ]
    private Transaction $transaction;

    public function __construct(
        #[Column(type: "text", nullable: true)] private string                                                                                            $description,
        #[ManyToOne(targetEntity: Account::class, fetch: "EAGER", inversedBy: "transactionRowDebtorSide"), JoinColumn(nullable: false)] private Account   $debtorsAccount,
        #[ManyToOne(targetEntity: Account::class, fetch: "EAGER", inversedBy: "transactionRowCreditorSide"), JoinColumn(nullable: false)] private Account $creditorsAccount,
        #[Column(type: "string")] private string                                                                                                          $amount,
        #[ManyToOne(targetEntity: AnalyticalAccount::class, fetch: "EAGER")] private ?AnalyticalAccount                                                   $debtorsAccountAnalytical = null,
        #[ManyToOne(targetEntity: AnalyticalAccount::class, fetch: "EAGER")] private ?AnalyticalAccount                                                   $creditorsAccountAnalytical = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
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

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDebtorsAccount(): ?Account
    {
        return $this->debtorsAccount;
    }

    public function getCreditorsAccount(): ?Account
    {
        return $this->creditorsAccount;
    }

    public function getDebtorsAccountAnalytical(): ?AnalyticalAccount
    {
        return $this->debtorsAccountAnalytical;
    }

    public function getCreditorsAccountAnalytical(): ?AnalyticalAccount
    {
        return $this->creditorsAccountAnalytical;
    }
}
