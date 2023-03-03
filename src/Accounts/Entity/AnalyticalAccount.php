<?php

namespace App\Accounts\Entity;

use App\Accounts\Repository\AnalyticalAccountRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: AnalyticalAccountRepository::class),
    Table(name: 'accounts_analytical'),
]
class AnalyticalAccount
{
    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    public function __construct(
        #[Column(type: "string")] private string $name,
        #[Column(type: "string", length: 3)] private string $numeral,
        #[
            ManyToOne(targetEntity: Account::class, inversedBy: "analyticals"),
            JoinColumn(nullable: true)
        ]
        private Account $account
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNumeral(): ?string
    {
        return $this->numeral;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }
}
