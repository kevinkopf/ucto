<?php

namespace App\Handler;

use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Contacts\Repository\ContactRepository;
use App\Service\FormService;
use App\Transactions\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TransactionCreationAlterationHandler
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private TransactionRepository $transactionRepository;
    private AccountRepository $accountRepository;
    private AnalyticalAccountRepository $analyticalAccountRepository;
    private ContactRepository $contactRepository;
    private FormService $formService;

    public function __construct(
        EventDispatcherInterface    $eventDispatcher,
        EntityManagerInterface      $entityManager,
        TransactionRepository       $transactionRepository,
        AccountRepository           $accountRepository,
        AnalyticalAccountRepository $analyticalAccountRepository,
        ContactRepository           $contactRepository,
        FormService                 $formService
    ) {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->analyticalAccountRepository = $analyticalAccountRepository;
        $this->contactRepository = $contactRepository;
        $this->formService = $formService;
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_transaction');

        if (!$payload['id']) {
            $transaction = $this->create($payload);
        } else {
            $transaction = $this->update($payload);
        }

        foreach ($payload['rows'] as $row) {
            $transactionRow = new \App\Transactions\Entity\TransactionRow(
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

    private function create(array $payload): \App\Transactions\Entity\Transaction
    {
        return new \App\Transactions\Entity\Transaction(
            $payload['description'],
            $payload['documentNumber'],
            $this->generateDateTime($payload['taxableSupplyDate']),
            $this->contactRepository->find($payload['contact']['id'])
        );
    }

    private function update(array $payload): \App\Transactions\Entity\Transaction
    {
        $transaction = $this->transactionRepository->find($payload['id']);

        if(!$transaction) {
            return $this->create($payload);
        }

        $transaction->update(
            $payload['description'],
            $payload['documentNumber'],
            $this->generateDateTime($payload['taxableSupplyDate']),
            $this->contactRepository->find($payload['contact']['id'])
        )
            ->removeRows()
        ;

        return $transaction;
    }

    private function generateDateTime(string $date): \DateTime
    {
        $dt = \DateTime::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $date);
        $dt->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dt->setTime(12, 0);

        return $dt;
    }
}
