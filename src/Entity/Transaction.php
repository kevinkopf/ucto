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
     * @Groups("transactions")
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
     * @Groups("transactions")
     * @ORM\Column(type="date")
     */
    private $taxableSupplyDate;

    /**
     * @Groups("transactions")
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @Groups("transactions")
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Transaction\Row",
     *     mappedBy="transaction",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *     )
     */
    private $rows;

    /**
     * Transaction constructor.
     * @param string|null $description
     * @param \DateTime $taxableSupplyDate
     * @param Contact $contact
     */
    public function __construct(
        ?string $description,
        \DateTime $taxableSupplyDate,
        Contact $contact
    ) {
        $this->description = $description;
        $this->taxableSupplyDate = $taxableSupplyDate;
        $this->contact = $contact;
        $this->rows = new ArrayCollection();
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
     * @return \DateTimeInterface|null
     */
    public function getTaxableSupplyDate(): ?\DateTimeInterface
    {
        return $this->taxableSupplyDate;
    }

    /**
     * @param \DateTimeInterface $taxableSupplyDate
     * @return $this
     */
    public function setTaxableSupplyDate(\DateTimeInterface $taxableSupplyDate): self
    {
        $this->taxableSupplyDate = $taxableSupplyDate;

        return $this;
    }

    /**
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact|null $contact
     * @return $this
     */
    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows->getValues();
    }

    /**
     * @param Row $row
     * @return $this
     */
    public function addRow(Row $row): self
    {
        if (!$this->rows->contains($row))
        {
            $this->rows[] = $row;
            $row->setTransaction($this);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function clearRows(): self
    {
        $this->rows = new ArrayCollection();

        return $this;
    }

    /**
     * @param Row $row
     * @return $this
     */
    public function removeRow(Row $row): self
    {
        if ($this->rows->contains($row))
        {
            $this->rows->removeElement($row);
            // set the owning side to null (unless already changed)
            if ($row->getTransaction() === $this)
            {
                $row->setTransaction(null);
            }
        }

        return $this;
    }
}
