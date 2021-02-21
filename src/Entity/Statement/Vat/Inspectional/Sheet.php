<?php

namespace App\Entity\Statement\Vat\Inspectional;

use App\Entity\Statement\Vat\Inspectional\Sheet\Record;
use App\Repository\Statement\Vat\Inspectional\SheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SheetRepository::class)
 * @ORM\Table(name="statements_vat_inspectional_sheets")
 */
class Sheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToMany(targetEntity=Record::class, orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinTable(
     *     name="statements_vat_inspectional_sheets_containing_records",
     *     joinColumns={@ORM\JoinColumn(name="sheet_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="record_id", referencedColumnName="id", unique=true)}
     *     )
     */
    private Collection $statementRecords;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $amount;

    public function __construct(array $statementRecords = [])
    {
        $this->statementRecords = new ArrayCollection($statementRecords);
        $this->amount = 0;

        /** @var Record $record */
        foreach ($statementRecords as $record) {
            $this->amount = bcadd($this->amount, $record->getAmount());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatementRecords(): array
    {
        return $this->statementRecords->getValues();
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
