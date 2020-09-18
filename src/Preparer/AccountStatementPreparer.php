<?php

namespace App\Preparer;

use App\Repository\AccountRepository;
use App\Repository\Transaction\RowRepository;
use Symfony\Component\HttpFoundation\Request;

class AccountStatementPreparer
{
    private RowRepository $rowRepository;

    public function __construct(RowRepository $rowRepository)
    {
        $this->rowRepository = $rowRepository;
    }

    public function prepare(Request $request): array
    {
        $account = $request->attributes->get('account');
        $year = $request->attributes->get('year');

        return $this->rowRepository->compileAccountStatement($account, (int)$year);
    }
}
