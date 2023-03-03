<?php

namespace App\Accounts\Entity;

use App\Accounts\Repository\AccountTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: AccountTypeRepository::class),
    Table(name: 'accounts_types'),
]
class AccountType
{
    public const TYPE_ASSET = "Aktivní";
    public const TYPE_LIABILITY = "Pasivní";
    public const TYPE_ASSET_AND_LIABILITY = "Aktivní i Pasivní";
    public const TYPE_EXPENSE_TAXABLE = "Nákladový daňový";
    public const TYPE_EXPENSE_NON_TAXABLE = "Nákladový nedaňový";
    public const TYPE_REVENUE_TAXABLE = "Výnosový daňový";
    public const TYPE_STATEMENT = "Závěrkový";

    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[OneToMany(
        mappedBy: "type",
        targetEntity: Account::class,
    )]
    private Collection $accounts;

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

    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
