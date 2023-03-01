<?php

namespace App\Entity;

use App\Entity\Transaction\Row;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(name="transactions")
 */
class Transaction
{
    /**
     * @Groups({"transactions", "contacts"})
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
     * @Groups("transactions")
     * @ORM\Column(type="datetime")
     */
    private \DateTime $taxableSupplyDate;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Contact $contact;

    /**
     * @Groups("transactions")
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Transaction\Row",
     *     mappedBy="transaction",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *     )
     */
    private Collection $rows;

    /**
     * @Groups("transactions")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $documentNumber;

    /**
     * Transaction constructor.
     * @param string $description
     * @param string $documentNumber
     * @param \DateTime $taxableSupplyDate
     * @param Contact $contact
     */
    public function __construct(
        string $description,
        string $documentNumber,
        \DateTime $taxableSupplyDate,
        Contact $contact
    ) {
        $this->update($description, $documentNumber, $taxableSupplyDate, $contact);

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTaxableSupplyDate(): \DateTimeInterface
    {
        return $this->taxableSupplyDate;
    }

    /**
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows->getValues();
    }

    public function addRow(Row $row): self
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
