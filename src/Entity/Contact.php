<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contacts")
 */
class Contact
{
    /**
     * @Groups({"contacts", "transactions"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups({"contacts", "transactions"})
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255)
     */
    private string $registrationNumber;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="boolean")
     */
    private bool $vatPayer;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private ?string $vatNumberPrefix;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $vatNumber;

    /**
     * @Groups("contacts")
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="contact")
     */
    private Collection $transactions;

    public function __construct(
        string $name,
        string $address,
        string $registrationNumber,
        bool $vatPayer = false,
        ?string $vatNumberPrefix = null,
        ?string $vatNumber = null,
        ?string $phone = null,
        ?string $email = null
    ) {
        $this->update(
            $name,
            $address,
            $registrationNumber,
            $vatPayer,
            $vatNumberPrefix,
            $vatNumber,
            $phone,
            $email
        );
        $this->transactions = new ArrayCollection();
    }

    public function update(
        string $name,
        string $address,
        string $registrationNumber,
        bool $vatPayer = false,
        ?string $vatNumberPrefix = null,
        ?string $vatNumber = null,
        ?string $phone = null,
        ?string $email = null
    ): self {
        $this->name = $name;
        $this->address = $address;
        $this->registrationNumber = $registrationNumber;
        $this->vatPayer = $vatPayer;
        $this->vatNumberPrefix = $vatNumberPrefix;
        $this->vatNumber = $vatNumber;
        $this->phone = $phone;
        $this->email = $email;

        return $this;
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
