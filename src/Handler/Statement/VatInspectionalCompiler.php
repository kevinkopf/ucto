<?php

namespace App\Handler\Statement;

use App\Enquirer\VatInspectionalEnquirer;
use App\Entity\Statement\Vat\Inspectional;
use App\Entity\Statement\Vat\Inspectional\Sheet;
use Doctrine\ORM\EntityManagerInterface;

class VatInspectionalCompiler
{
    private EntityManagerInterface $em;
    private VatInspectionalEnquirer $vatInspectionalEnquirer;

    public function __construct(EntityManagerInterface $em, VatInspectionalEnquirer $vatInspectionalEnquirer)
    {
        $this->em = $em;
        $this->vatInspectionalEnquirer = $vatInspectionalEnquirer;
    }

    public function compile(int $year, int $month): void
    {
        $inspectionalStatement = new Inspectional(
            $year,
            $month,
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectA1($year, $month)
                )
            ),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectA2($year, $month)
                )
            ),
            new Sheet(),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectA4($year, $month)
                )
            ),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectA5($year, $month)
                )
            ),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectB1($year, $month)
                )
            ),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectB2($year, $month)
                )
            ),
            new Sheet(
                $this->createSheetFromTransactionRows(
                    $this->vatInspectionalEnquirer->selectB3($year, $month)
                )
            )
        );

        $this->em->persist($inspectionalStatement);
        $this->em->flush();
    }

    private function createSheetFromTransactionRows(array $transactionRows): array
    {
        $sheetRecords = [];

        /** @var Transaction\Row $row */
        foreach ($transactionRows as $row) {
            $sheetRecords[] = new Sheet\Record($row->getTransaction(), $row->getAmount());
        }

        return $sheetRecords;
    }
}
