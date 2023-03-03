<?php

namespace App\Accounts\Actor;

use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractAccountDetailActor
{
    public function __construct(
        private readonly AccountRepository|AnalyticalAccountRepository $repository
    )
    {
    }

    public function search(Request $request): array
    {
        $search = $request->request->get('search');

        if (!$search) {
            throw new BadRequestHttpException();
        }

        return $this->repository->findSimilarByNameOrNumeral($search);
    }
}