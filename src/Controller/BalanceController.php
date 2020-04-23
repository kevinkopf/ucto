<?php

namespace App\Controller;

use App\Entity;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BalanceController extends AbstractController
{
    /**
     * @Route("/obratova_predvaha", name="trial_balance")
     * @param AccountRepository $accountRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AccountRepository $accountRepository)
    {
        $accounts = $accountRepository->findAllThisYearGroupedByAccount(date('Y'));
        $filteredAccounts = $this->filterAccounts($accounts);

        return $this->render('page.trialBalance.html.twig', [
            'accounts' => $filteredAccounts,
        ]);
    }

    /**
     * TODO: Separate those private functions into a service maybe? Later though.
     */

    /**
     * @param $accounts
     * @return array
     */
    private function filterAccounts($accounts)
    {
        return [
            Entity\Account\Type::TYPE_ASSET =>
                $this->mapFilteredAccounts($accounts, Entity\Account\Type::TYPE_ASSET),
            Entity\Account\Type::TYPE_LIABILITY =>
                $this->mapFilteredAccounts(
                    $accounts,
                    Entity\Account\Type::TYPE_LIABILITY,
                    Entity\Account\Type::TYPE_ASSET_AND_LIABILITY
                ),
            Entity\Account\Type::TYPE_EXPENSE_TAXABLE =>
                $this->mapFilteredAccounts($accounts, Entity\Account\Type::TYPE_EXPENSE_TAXABLE),
            Entity\Account\Type::TYPE_EXPENSE_NON_TAXABLE =>
                $this->mapFilteredAccounts($accounts, Entity\Account\Type::TYPE_EXPENSE_NON_TAXABLE),
            Entity\Account\Type::TYPE_REVENUE_TAXABLE =>
                $this->mapFilteredAccounts($accounts, Entity\Account\Type::TYPE_REVENUE_TAXABLE),
        ];
    }

    /**
     * @param array $filteredAccounts
     * @param mixed ...$types
     * @return array
     */
    private function mapFilteredAccounts(array $filteredAccounts, ...$types): array
    {
        return array_filter(
            array_map(function ($account) use ($types) {
                if(in_array($account['type'], $types))
                {
                    $account['totalEndAmount'] =
                        (string)
                        ((int) $account['totalBeginAmount'] +
                            ((int) $account['totalDebtorAmount'] -
                                (int) $account['totalCreditorAmount']) *
                            (in_array(
                                $account['type'],
                                [
                                    Entity\Account\Type::TYPE_LIABILITY,
                                    Entity\Account\Type::TYPE_ASSET_AND_LIABILITY,
                                    Entity\Account\Type::TYPE_REVENUE_TAXABLE,
                                ]
                            ) ? -1 : 1)
                        );

                    return $account;
                }
            }, $filteredAccounts),
            function($account) {
                return $account != null;
            }
        );
    }
}
