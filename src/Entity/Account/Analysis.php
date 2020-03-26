<?php

namespace App\Entity\Account;

use App\Entity\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AnalysisRepository")
 * @ORM\Table(name="accounts_analytical")
 */
class Analysis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $numeral;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="analyticals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

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
    public function getNumeral(): ?string
    {
        return $this->numeral;
    }

    /**
     * @param string $numeral
     * @return $this
     */
    public function setNumeral(string $numeral): self
    {
        $this->numeral = $numeral;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     * @return $this
     */
    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
