<?php

namespace App\Handler\Transaction;

use App\Entity;
use App\Repository\TransactionRepository;
use App\Requisition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AddOrEdit
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private TransactionRepository $transactionRepository;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        TransactionRepository $transactionRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param Requisition\TransactionAddOrEdit $requisition
     */
    public function handle(Requisition\TransactionAddOrEdit $requisition)
    {
        if(!$requisition->id)
        {
            $this->create($requisition);
        }
        else
        {
            $this->update($requisition);
        }
    }

    /**
     * @param Requisition\TransactionAddOrEdit $requisition
     */
    private function create(Requisition\TransactionAddOrEdit $requisition): void
    {
        $transaction = new Entity\Transaction(
            $requisition->description,
            $requisition->documentNumber,
            $requisition->taxableSupplyDate,
            $requisition->contact
        );

        foreach($requisition->rows as $row)
        {
            if(!$row->getDescription())
            {
                $row->setDescription($transaction->getDescription());
            }

            $transaction->addRow($row);

            $this->entityManager->persist($row);
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    private function update(Requisition\TransactionAddOrEdit $requisition): void
    {
        $transaction = $this->transactionRepository->find($requisition->id);
        $transaction
            ->setDescription($requisition->description)
            ->setDocumentNumber($requisition->documentNumber)
            ->setTaxableSupplyDate($requisition->taxableSupplyDate)
            ->setContact($requisition->contact)
            ->clearRows();

        foreach($requisition->rows as $row)
        {
            if(!$row->getDescription())
            {
                $row->setDescription($transaction->getDescription());
            }

            $transaction->addRow($row);

            $this->entityManager->persist($row);
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
