<?php

namespace App\Transactions\Actor;

use App\Transactions\Exception\TransactionNotFoundException;
use App\Transactions\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionDetailActor
{
    public function __construct(
        private TransactionRepository $transactionRepository
    ) {
    }

    public function prepare(Request $request): array
    {
        $id = (int)$request->request->get('id');

        if (!$id) {
            throw new BadRequestHttpException();
        }

        $transaction = $this->transactionRepository->find($id);

        if (!$transaction) {
            throw new TransactionNotFoundException();
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
                ],
                'creditorsAccount' => [
                    'id' => $transactionRow->getCreditorsAccount()->getId(),
                    'name' => $transactionRow->getCreditorsAccount()->getName(),
                    'numeral' => $transactionRow->getCreditorsAccount()->getNumeral(),
                ],
                'debtorsAnalyticalAccount' => [
                    'id' => $transactionRow->getDebtorsAccountAnalytical()?->getId(),
                    'name' => $transactionRow->getDebtorsAccountAnalytical()?->getName(),
                    'numeral' => $transactionRow->getDebtorsAccountAnalytical()?->getNumeral(),
                ],
                'creditorsAnalyticalAccount' => [
                    'id' => $transactionRow->getCreditorsAccountAnalytical()?->getId(),
                    'name' => $transactionRow->getCreditorsAccountAnalytical()?->getName(),
                    'numeral' => $transactionRow->getCreditorsAccountAnalytical()?->getNumeral(),
                ],
            ];
        }

        return $result;
    }
}
