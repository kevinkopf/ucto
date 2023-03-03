<?php

namespace App\Contacts\Entity;

use App\Contacts\Repository\ContactRepository;
use App\Entity\Updatabale;
use App\Transactions\Entity\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: ContactRepository::class),
    Table(name: 'contacts'),
]
class Contact extends Updatabale
{
    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[OneToMany(
        mappedBy: "contact",
        targetEntity: Transaction::class,
    )]
    private Collection $transactions;

    public function __construct(
        #[Column(type: "string")] private string $name,
        #[Column(type: "string")] private string $address,
        #[Column(type: "string")] private string $registrationNumber,
        #[Column(type: "boolean")] private bool $vatPayer = false,
        #[Column(type: "string", length: 3)] private ?string $vatNumberPrefix = null,
        #[Column(type: "string", length: 20)] private ?string $vatNumber = null,
        #[Column(type: "string")] private ?string $phone = null,
        #[Column(type: "string")] private ?string $email = null
    ) {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    public function isVatPayer(): bool
    {
        return $this->vatPayer;
    }

    public function getVatNumberPrefix(): ?string
    {
        return $this->vatNumberPrefix;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function getTransactions(): array
    {
        return $this->transactions->getValues();
    }
}
