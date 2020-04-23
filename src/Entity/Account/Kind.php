<?php

namespace App\Entity\Account;

use App\Entity\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\KindRepository")
 * @ORM\Table(name="accounts_kinds")
 */
class Kind
{
    public const KIND_BALANCE = "Rozvahový";
    public const KIND_INCOME_STATEMENT = "Výsledkový";

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
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="kind", orphanRemoval=true)
     */
    private $accounts;

    /**
     * Kind constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->accounts = new ArrayCollection();
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
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account))
        {
            $this->accounts[] = $account;
            $account->setKind($this);
        }

        return $this;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account))
        {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getKind() === $this)
            {
                $account->setKind(null);
            }
        }

        return $this;
    }
}
