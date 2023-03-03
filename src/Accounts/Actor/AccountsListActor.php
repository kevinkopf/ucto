<?php

namespace App\Accounts\Actor;

use App\Accounts\Entity\Account;
use App\Accounts\Entity\AnalyticalAccount;
use App\Accounts\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\Request;

class AccountsListActor
{
    public function __construct(
        private AccountRepository $repository
    )
    {
    }

    public function prepare(Request $request): array
    {
        $search = (string)$request->request->get('search');
        $accounts = $this->repository->findSimilarByNameOrNumeral($search);

        $result = [
            'data' => [],
        ];

        /** @var Account $account */
        foreach ($accounts as $account) {
            $preparedAccount = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'numeral' => $account->getNumeral(),
                'type' => $account->getType()->getName(),
                'analyticals' => [],
            ];

            /** @var AnalyticalAccount $analyticalAccount */
            foreach ($account->getAnalyticals() as $analyticalAccount) {
                $preparedAccount['analyticals'][] = [
                    'id' => $analyticalAccount->getId(),
                    'name' => $analyticalAccount->getName(),
                    'numeral' => $analyticalAccount->getNumeral(),
                ];
            }

            $result['data'][] = $preparedAccount;
        }

        return $result;
    }
}