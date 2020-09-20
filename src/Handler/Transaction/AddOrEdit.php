<?php

namespace App\Handler\Transaction;

use App\Entity;
use App\Repository\AccountRepository;
use App\Repository\ContactRepository;
use App\Repository\TransactionRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AddOrEdit
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private TransactionRepository $transactionRepository;
    private AccountRepository $accountRepository;
    private ContactRepository $contactRepository;
    private FormService $formService;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        ContactRepository $contactRepository,
        FormService $formService
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->contactRepository = $contactRepository;
        $this->formService = $formService;
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_transaction');

        if ($payload['id']) {
            $transaction = $this->create($payload);
        } else {
            $transaction = $this->update($payload);
        }

        foreach ($payload['rows'] as $row) {
            $transactionRow = new Entity\Transaction\Row(
                $row['description'] ?: $payload['description'],
                $this->accountRepository->find($row['debtorsAccount']['id']),
                $this->accountRepository->find($row['creditorsAccount']['id']),
                $row['amount']
            );

            $transaction->addRow($transactionRow);
            $this->entityManager->persist($transactionRow);
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    private function create(array $payload): Entity\Transaction
    {
        return new Entity\Transaction(
            $payload['description'],
            $payload['documentNumber'],
            \DateTime::createFromFormat('Y-m-d', $payload['taxableSupplyDate']),
            $this->contactRepository->find($payload['contact']['id'])
        );
    }

    private function update(array $payload): Entity\Transaction
    {
        $transaction = $this->transactionRepository->find($payload['id']);

        if(!$transaction) {
            return $this->create($payload);
        }

        $transaction->update(
            $payload['description'],
            $payload['documentNumber'],
            $payload['taxableSupplyDate'],
            $this->contactRepository->find($payload['contact']['id'])
        );

        return $transaction;
    }
}
