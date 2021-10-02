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
        $offset = (($page - 1) * $limit)-1;
        $offset = $offset < 0 ? 0 : $offset;

        $transactions = [
            'data' => [],
            'pages' => [
                'current' => $page,
                'total' => ceil($this->transactionRepository->count([]) / $limit),
            ],
        ];

        $preparedTransactions = $this->transactionRepository->findBy(
            [],
            ['taxableSupplyDate' => 'DESC'],
            $limit,
            $offset
        );

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
                $row = [
                    'description' => $transactionRow->getDescription(),
                    'amount' => $transactionRow->getAmount(),
                    'debtorsAccount' => [
                        'numeral' => $transactionRow->getDebtorsAccount()->getNumeral(),
                        'name' => $transactionRow->getDebtorsAccount()->getName(),
                    ],
                    'creditorsAccount' => [
                        'numeral' => $transactionRow->getCreditorsAccount()->getNumeral(),
                        'name' => $transactionRow->getCreditorsAccount()->getName(),
                    ],
                ];

                if ($transactionRow->getDebtorsAccountAnalytical()) {
                    $row['debtorsAnalyticalAccount'] = [
                        'numeral' => $transactionRow->getDebtorsAccountAnalytical()->getNumeral(),
                        'name' => $transactionRow->getDebtorsAccountAnalytical()->getName(),
                    ];
                }

                if ($transactionRow->getCreditorsAccountAnalytical()) {
                    $row['creditorsAnalyticalAccount'] = [
                        'numeral' => $transactionRow->getCreditorsAccountAnalytical()->getNumeral(),
                        'name' => $transactionRow->getCreditorsAccountAnalytical()->getName(),
                    ];
                }

                $displayTransaction['rows'][] = $row;
            }

            $transactions['data'][] = $displayTransaction;
        }

        return $transactions;
    }
}
