<?php

namespace App\Accounts\Actor;

use App\Accounts\Entity\AnalyticalAccount;
use App\Accounts\Exception\AccountNotFoundException;
use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AnalyticalAccountListActor
{
    public function __construct(
        private AccountRepository $accountRepository,
        private AnalyticalAccountRepository $analyticalAccountRepository,
    )
    {
    }

    public function prepare(Request $request): array
    {
        $accountId = (int) $request->request->get('account');
        if (!$accountId) {
            throw new BadRequestHttpException();
        }

        $account = $this->accountRepository->find($accountId);
        if (!$account) {
            throw new AccountNotFoundException();
        }

        $nameOrNumeral = (string)$request->request->get('search');
        $analyticalAccounts = $this->analyticalAccountRepository->findSimilarByNameOrNumeral($account->getId(), $nameOrNumeral);

        $result = [];

        /** @var AnalyticalAccount $account */
        foreach ($analyticalAccounts as $account) {
            $result[] = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'numeral' => $account->getNumeral(),
            ];
        }

        return $result;
    }
}