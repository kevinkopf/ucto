<?php

namespace App\Entity;

use App\Entity\Account\Analytical;
use App\Entity\Account\Kind;
use App\Entity\Account\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="accounts")
 */
class Account
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Type", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $numeral;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Analytical", mappedBy="account", orphanRemoval=true)
     */
    private $analyticals;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Kind", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $kind;

    /**
     * Account constructor.
     * @param string $numeral
     * @param string $name
     * @param Type $type
     * @param Kind|null $kind
     */
    public function __construct(string $numeral, string $name, Type $type, ?Kind $kind = null)
    {
        $this->numeral = $numeral;
        $this->name = $name;
        $this->type = $type;
        $this->kind = $kind;
        $this->analyticals = new ArrayCollection();
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
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
     * @return $this
     */
    public function setType(?Type $type): self
    {
        $this->type = $type;

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
     * @return Collection|Analytical[]
     */
    public function getAnalyticals(): Collection
    {
        return $this->analyticals;
    }

    /**
     * @param Analytical $analytical
     * @return $this
     */
    public function addAnalytical(Analytical $analytical): self
    {
        if (!$this->analyticals->contains($analytical))
        {
            $this->analyticals[] = $analytical;
            $analytical->setAccount($this);
        }

        return $this;
    }

    /**
     * @param Analytical $analytical
     * @return $this
     */
    public function removeAnalytical(Analytical $analytical): self
    {
        if ($this->analyticals->contains($analytical))
        {
            $this->analyticals->removeElement($analytical);
            // set the owning side to null (unless already changed)
            if ($analytical->getAccount() === $this)
            {
                $analytical->setAccount(null);
            }
        }

        return $this;
    }

    public function getKind(): ?Kind
    {
        return $this->kind;
    }

    public function setKind(?Kind $kind): self
    {
        $this->kind = $kind;

        return $this;
    }
}
