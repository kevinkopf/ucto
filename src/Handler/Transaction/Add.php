<?php

namespace App\Handler\Transaction;

use App\Entity;
use App\Requisition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Add
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    public function handle(Requisition\TransactionAddOrEdit $requisition)
    {
        $transaction = new Entity\Transaction(
            $requisition->description,
            $requisition->taxableSupplyDate,
            $requisition->contact
        );

        foreach($requisition->rows as $row)
        {
            $transaction->addRow($row);

            $this->entityManager->persist($row);
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
