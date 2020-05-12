<?php

namespace App\Handler\Account;

use App\Entity;
use App\Repository\Account\AnalyticalRepository;
use App\Requisition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Analytical
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private AnalyticalRepository $analyticalRepository;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     * @param AnalyticalRepository $analyticalRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        AnalyticalRepository $analyticalRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->analyticalRepository = $analyticalRepository;
    }

    public function handle(Requisition\Account\Analytical $requisition)
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
     * @param Requisition\Account\Analytical $requisition
     */
    private function create(Requisition\Account\Analytical $requisition): void
    {
        $analyticalAccount = new Entity\Account\Analytical(
            $requisition->name,
            $requisition->numeral
        );

        $requisition->account->addAnalytical($analyticalAccount);

        $this->entityManager->persist($analyticalAccount);
        $this->entityManager->flush();
    }

    /**
     * @param Requisition\Account\Analytical $requisition
     */
    private function update(Requisition\Account\Analytical $requisition): void
    {
        $analyticalAccount = $this->analyticalRepository->find($requisition->id);
        $analyticalAccount
            ->setName($requisition->name)
            ->setNumeral($requisition->numeral);

        $this->entityManager->flush();
    }
}
