<?php

namespace App\Preparer;

use App\Transactions\Repository\TransactionRowRepository;
use Symfony\Component\HttpFoundation\Request;

class AccountStatementPreparer
{
    private TransactionRowRepository $rowRepository;

    public function __construct(TransactionRowRepository $rowRepository)
    {
        $this->rowRepository = $rowRepository;
    }

    public function prepare(Request $request): array
    {
        $account = $request->attributes->get('account');
        $account = $account ?: '221';
        $year = $request->attributes->get('year');
        $year = $year ?: (new \DateTime())->format('Y');

        return $this->rowRepository->compileAccountStatement($account, $year);
    }
}
