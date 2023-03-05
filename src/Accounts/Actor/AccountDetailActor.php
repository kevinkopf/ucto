<?php

namespace App\Accounts\Actor;

use App\Accounts\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AccountDetailActor
{
    public function __construct(
        private AccountRepository $accountRepository
    )
    {
    }

    public function search(Request $request): array
    {
        $search = $request->request->get('search');

        if (!$search) {
            throw new BadRequestHttpException();
        }

        return $this->accountRepository->findSimilarByNameOrNumeral($search);
    }
}