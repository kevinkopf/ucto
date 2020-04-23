<?php

namespace App\Entity\Account;

use App\Entity\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\TypeRepository")
 * @ORM\Table(name="accounts_types")
 */
class Type
{
    public const TYPE_ASSET = "Aktivní";
    public const TYPE_LIABILITY = "Pasivní";
    public const TYPE_ASSET_AND_LIABILITY = "Aktivní i Pasivní";
    public const TYPE_EXPENSE_TAXABLE = "Nákladový daňový";
    public const TYPE_EXPENSE_NON_TAXABLE = "Nákladový nedaňový";
    public const TYPE_REVENUE_TAXABLE = "Výnosový daňový";
    public const TYPE_STATEMENT = "Závěrkový";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="type", orphanRemoval=true)
     */
    private $accounts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Type constructor.
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
            $account->setType($this);
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
            if ($account->getType() === $this)
            {
                $account->setType(null);
            }
        }

        return $this;
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
}
