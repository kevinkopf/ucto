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
    private $id;

    /**
     * @Groups({"contacts", "transactions"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=255)
     */
    private $registrationNumber;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="boolean")
     */
    private $vatPayer;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $vatNumberPrefix;

    /**
     * @Groups("contacts")
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $vatNumber;

    /**
     * @Groups("contacts")
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="contact")
     */
    private $transactions;

    /**
     * @param string $name
     * @param string $address
     * @param string $registrationNumber
     * @param bool $vatPayer
     * @param string|null $vatNumberPrefix
     * @param string|null $vatNumber
     */
    public function __construct(
        string $name,
        string $address,
        string $registrationNumber,
        bool $vatPayer = false,
        ?string $vatNumberPrefix = null,
        ?string $vatNumber = null
    ) {
        $this->name = $name;
        $this->address = $address;
        $this->registrationNumber = $registrationNumber;
        $this->vatPayer = $vatPayer;
        $this->vatNumberPrefix = $vatNumberPrefix;
        $this->vatNumber = $vatNumber;
        $this->transactions = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    /**
     * @param string $registrationNumber
     * @return $this
     */
    public function setRegistrationNumber(string $registrationNumber): self
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVatPayer(): bool
    {
        return $this->vatPayer;
    }

    /**
     * @param bool $isVatPayer
     * @return self
     */
    public function setVatPayer(bool $isVatPayer): self
    {
        $this->vatPayer = $isVatPayer;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVatNumberPrefix(): ?string
    {
        return $this->vatNumberPrefix;
    }

    /**
     * @param string|null $vatNumberPrefix
     * @return $this
     */
    public function setVatNumberPrefix(?string $vatNumberPrefix): self
    {
        $this->vatNumberPrefix = $vatNumberPrefix;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    /**
     * @param string|null $vatNumber
     * @return $this
     */
    public function setVatNumber(?string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions->getValues();
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction))
        {
            $this->transactions[] = $transaction;
            $transaction->setContact($this);
        }

        return $this;
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction))
        {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getContact() === $this)
            {
                $transaction->setContact(null);
            }
        }

        return $this;
    }
}
