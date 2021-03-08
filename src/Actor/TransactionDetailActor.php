<?php

namespace App\Actor;

use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;

class TransactionDetailActor
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function prepare(Request $request): array
    {
        $id = (int)$request->request->get('id');
        $transaction = $this->transactionRepository->find($id);

        if (!$transaction) {
            return [];
        }

        $result = [
            'id' => $transaction->getId(),
            'description' => $transaction->getDescription(),
            'documentNumber' => $transaction->getDocumentNumber(),
            'taxableSupplyDate' => $transaction->getTaxableSupplyDate()->format('d.m.Y'),
            'contact' => [
                'id' => $transaction->getContact()->getId(),
                'name' => $transaction->getContact()->getName(),
            ],
            'rows' => [],
        ];

        foreach ($transaction->getRows() as $transactionRow) {
            $result['rows'][] = [
                'description' => $transactionRow->getDescription(),
                'amount' => $transactionRow->getAmount(),
                'debtorsAccount' => [
                    'id' => $transactionRow->getDebtorsAccount()->getId(),
                    'name' => $transactionRow->getDebtorsAccount()->getName(),
                    'numeral' => $transactionRow->getDebtorsAccount()->getNumeral(),
//                    'analyticals' => $transactionRow->getDebtorsAccount()->getAnalyticals(),
                ],
                'creditorsAccount' => [
                    'id' => $transactionRow->getCreditorsAccount()->getId(),
                    'name' => $transactionRow->getCreditorsAccount()->getName(),
                    'numeral' => $transactionRow->getCreditorsAccount()->getNumeral(),
//                    'analyticals' => $transactionRow->getCreditorsAccount()->getAnalyticals(),
                ],
                'debtorsAnalyticalAccount' => [
                    'id' => $transactionRow->getDebtorsAccountAnalytical()->getId(),
                    'name' => $transactionRow->getDebtorsAccountAnalytical()->getName(),
                    'numeral' => $transactionRow->getDebtorsAccountAnalytical()->getNumeral(),
                ],
                'creditorsAnalyticalAccount' => [
                    'id' => $transactionRow->getCreditorsAccountAnalytical()->getId(),
                    'name' => $transactionRow->getCreditorsAccountAnalytical()->getName(),
                    'numeral' => $transactionRow->getCreditorsAccountAnalytical()->getNumeral(),
                ],
            ];
        }

        return $result;
    }
}
