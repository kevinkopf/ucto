<?php

namespace App\Accounts\Actor;

use App\Accounts\Repository\AnalyticalAccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AnalyticalAccountDetailActor
{
    public function __construct(
        private AnalyticalAccountRepository $accountRepository
    )
    {
    }

    public function search(Request $request): array
    {
        $search = $request->request->get('search');
        $accountId = (int) $request->request->get('account');

        if (!$search || !$accountId) {
            throw new BadRequestHttpException();
        }

        return $this->accountRepository->findSimilarByNameOrNumeral($accountId, $search);
    }
}