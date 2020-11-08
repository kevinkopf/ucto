<?php

namespace App\Entity\Account;

use App\Entity\Account;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AnalyticalRepository")
 * @ORM\Table(name="accounts_analytical")
 */
class Analytical
{
    /**
     * @Groups({"accounts"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups({"accounts"})
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @Groups({"accounts"})
     * @ORM\Column(type="string", length=3)
     */
    private string $numeral;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="analyticals")
     * @ORM\JoinColumn(nullable=false)
     */
    private Account $account;

    public function __construct(string $name, string $numeral, Account $account)
    {
        $this->name = $name;
        $this->numeral = $numeral;
        $this->account = $account;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumeral(): ?string
    {
        return $this->numeral;
    }

    public function setNumeral(string $numeral): self
    {
        $this->numeral = $numeral;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
