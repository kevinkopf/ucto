<?php

namespace App\Transactions\Handler;

use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Contacts\Repository\ContactRepository;
use App\Service\DateGenerator;
use App\Service\FormService;
use App\Transactions\Entity\Transaction;
use App\Transactions\Entity\TransactionRow;
use App\Transactions\Exception\TransactionNotFoundException;
use App\Transactions\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TransactionHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AccountRepository $accountRepository,
        private AnalyticalAccountRepository $analyticalAccountRepository,
        private ContactRepository $contactRepository,
        private TransactionRepository $transactionRepository,
        private FormService $formService,
        private DateGenerator $dateGenerator,
    ) {
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_transaction');

        if ($payload['id']) {
            $transaction = $this->transactionRepository->find($payload['id']);

            if (!$transaction) {
                throw new TransactionNotFoundException();
            }

            $this->entityManager->remove($transaction);
        }

        $transaction = new Transaction(
            $payload['description'],
            $payload['documentNumber'],
            $this->dateGenerator->generateDateTime($payload['taxableSupplyDate']),
            $this->contactRepository->find($payload['contact']['id'])
        );

        foreach ($payload['rows'] as $row) {
            $transactionRow = new TransactionRow(
                $row['description'] ?: $payload['description'],
                $this->accountRepository->find($row['debtorsAccount']['id']),
                $this->accountRepository->find($row['creditorsAccount']['id']),
                $row['amount'],
                is_array($row['debtorsAnalyticalAccount']) && $row['debtorsAnalyticalAccount']['id'] ? $this->analyticalAccountRepository->find($row['debtorsAnalyticalAccount']['id']) : null,
                is_array($row['creditorsAnalyticalAccount']) && $row['creditorsAnalyticalAccount']['id'] ? $this->analyticalAccountRepository->find($row['creditorsAnalyticalAccount']['id']) : null,
            );

            $transaction->addRow($transactionRow);
            $this->entityManager->persist($transactionRow);
        }

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
