<?php

namespace App\Entity\Statement\Vat\Inspectional\Sheet;

use App\Repository\Statement\Vat\Inspectional\Sheet\RecordRepository;
use App\Transactions\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 * @ORM\Table(name="statements_vat_inspectional_sheets_records")
 */
class Record
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Transaction $transaction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $amount;

    public function __construct(Transaction $transaction, string $amount)
    {
        $this->transaction = $transaction;
        $this->amount = $amount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
