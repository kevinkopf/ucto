<?php

namespace App\Transactions\Entity;

use App\Contacts\Entity\Contact;
use App\Transactions\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[
    Entity(repositoryClass: TransactionRepository::class),
    Table(name: 'transactions'),
]
class Transaction
{
    #[
        Column(type: 'integer'),
        Id,
        GeneratedValue(strategy: 'AUTO'),
    ]
    private ?int $id;

    #[OneToMany(
        mappedBy: "transaction",
        targetEntity: TransactionRow::class,
        cascade: ["persist"],
        orphanRemoval: true
    )]
    private Collection $rows;

    public function __construct(
        #[Column(type: "text")]
        private string $description,

        #[Column(type: "string")]
        private string $documentNumber,

        #[Column(type: "datetime")]
        private \DateTime $taxableSupplyDate,

        #[ManyToOne(targetEntity: Contact::class, inversedBy: "transactions")]
        private Contact $contact,
    ) {
        $this->rows = new ArrayCollection();
    }

    public function update(
        string $description,
        string $documentNumber,
        \DateTime $taxableSupplyDate,
        Contact $contact
    ): self {
        $this->description = $description;
        $this->documentNumber = $documentNumber;
        $this->taxableSupplyDate = $taxableSupplyDate;
        $this->contact = $contact;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTaxableSupplyDate(): \DateTimeInterface
    {
        return $this->taxableSupplyDate;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getRows(): array
    {
        return $this->rows->getValues();
    }

    public function addRow(TransactionRow $row): self
    {
        if (!$this->rows->contains($row))
        {
            $this->rows[] = $row;
            $row->setTransaction($this);
        }

        return $this;
    }

    public function removeRows(): self
    {
        $this->rows = new ArrayCollection();

        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }
}
