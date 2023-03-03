<?php

namespace App\Accounts\Entity;

use App\Accounts\Repository\AccountKindRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: AccountKindRepository::class),
    Table(name: 'accounts_kinds'),
]
class AccountKind
{
    public const KIND_BALANCE = "Rozvahový";
    public const KIND_INCOME_STATEMENT = "Výsledkový";

    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[OneToMany(
        mappedBy: "kind",
        targetEntity: Account::class,
    )]
    private $accounts;

    public function __construct(
        #[Column(type: "string")] private string $name
    )
    {
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAccounts(): Collection
    {
        return $this->accounts;
    }
}
