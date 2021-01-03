<?php

namespace App\Entity\Statement;

use App\Repository\Statement\TrialBalanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrialBalanceRepository::class)
 */
class TrialBalance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTime $compiledToDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $compiledAt;

    /**
     * @ORM\OneToMany(targetEntity=TrialBalanceRecord::class, mappedBy="trialBalance", orphanRemoval=true)
     */
    private $records;

    public function __construct(\DateTime $compiledToDate)
    {
        $this->compiledToDate = $compiledToDate;
        $this->compiledAt = new \DateTime();
        $this->records = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompiledToDate(): \DateTimeInterface
    {
        return $this->compiledToDate;
    }

    public function getCompiledAt(): \DateTimeInterface
    {
        return $this->compiledAt;
    }

    public function getRecords(): array
    {
        return $this->records->getValues();
    }

    public function addRecord(TrialBalanceRecord $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setTrialBalance($this);
        }

        return $this;
    }

    public function removeRecord(TrialBalanceRecord $record): self
    {
        if ($this->records->contains($record)) {
            $this->records->removeElement($record);
            // set the owning side to null (unless already changed)
            if ($record->getTrialBalance() === $this) {
                $record->setTrialBalance(null);
            }
        }

        return $this;
    }
}
