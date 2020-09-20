<?php

namespace App\Actor;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;

class TransactionsListActor
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function prepare(Request $request): array
    {
        $page = (int)$request->request->get('page');
        $page = $page < 1 ? 1 : $page;
        $limit = (int)$request->request->get('limit');
        $limit = $limit < 50 ? 100 : $limit;

        $transactions = [
            'data' => [],
            'pages' => [
                'current' => $page,
                'total' => ceil($this->transactionRepository->count([]) / $limit),
            ],
        ];

        $preparedTransactions = $this->transactionRepository->findBy([], ['taxableSupplyDate' => 'DESC'], $limit,
            ($page - 1) * $limit);

        foreach ($preparedTransactions as $transaction) {
            $displayTransaction = [
                'id' => $transaction->getId(),
                'description' => $transaction->getDescription(),
                'documentNumber' => $transaction->getDocumentNumber(),
                'taxableSupplyDate' => $transaction->getTaxableSupplyDate()->format('d.m.Y'),
                'contact' => [
                    'name' => $transaction->getContact()->getName(),
                    'vatNumberPrefix' => $transaction->getContact()->getVatNumberPrefix(),
                    'vatNumber' => $transaction->getContact()->getVatNumber(),
                ],
                'rows' => [],
            ];

            foreach ($transaction->getRows() as $transactionRow) {
                $displayTransaction['rows'][] = [
                    'description' => $transactionRow->getDescription(),
                    'amount' => $transactionRow->getAmount(),
                    'debtorsAccount' => $transactionRow->getDebtorsAccount()->getNumeral(),
                    'creditorsAccount' => $transactionRow->getCreditorsAccount()->getNumeral(),
                ];
            }

            $transactions['data'][] = $displayTransaction;
        }

        return $transactions;
    }
}
