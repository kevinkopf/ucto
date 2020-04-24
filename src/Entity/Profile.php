<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $taxNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vatPayer;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $vatNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileNumber;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    /**
     * @param string $taxNumber
     * @return self
     */
    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVatPayer(): bool
    {
        return $this->vatPayer;
    }

    /**
     * @param bool $vatPayer
     * @return self
     */
    public function setVatPayer(bool $vatPayer): self
    {
        $this->vatPayer = $vatPayer;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     * @return self
     */
    public function setVatNumber(string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileNumber(): string
    {
        return $this->fileNumber;
    }

    /**
     * @param string $fileNumber
     * @return self
     */
    public function setFileNumber(string $fileNumber): self
    {
        $this->fileNumber = $fileNumber;

        return $this;
    }
}
