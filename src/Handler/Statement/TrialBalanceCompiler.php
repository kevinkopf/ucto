<?php

namespace App\Handler\Statement;

use App\Accounts\Entity\AccountType;
use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Entity;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TrialBalanceCompiler
{
    public function __construct(
        private AccountRepository           $accountRepository,
        private AnalyticalAccountRepository $analyticalRepository,
        private EntityManagerInterface      $em,
    )
    {
    }

    public function compile(array $payload)
    {
        $year = (int)$payload['year'];
        $month = (int)$payload['month'];
        $day = (int)$payload['day'];

        $accounts = $this->accountRepository->compileTrialBalance($year, $month, $day);
        $this->em->beginTransaction();

        $trialBalance = new Entity\Statement\TrialBalance(
            (new DateTime())->setDate($year, $month, $day)
        );
        $this->em->persist($trialBalance);
        $this->em->flush();

        foreach ($accounts as $account) {
            $foundAccount = $this->accountRepository->find($account['id']);

            if (!$foundAccount) {
                $this->em->rollback();
                throw new Exception('Account not found. This is strange...');
            }

            if (in_array($foundAccount->getType()->getName(), [
                AccountType::TYPE_ASSET,
                AccountType::TYPE_ASSET_AND_LIABILITY,
                AccountType::TYPE_EXPENSE_TAXABLE,
                AccountType::TYPE_EXPENSE_NON_TAXABLE,
            ], true)) {
                $closingAmount = $account['openingAmount'] + $account['debtorAmount'] - $account['creditorAmount'];
            } elseif (in_array($foundAccount->getType()->getName(), [
                AccountType::TYPE_LIABILITY,
                AccountType::TYPE_REVENUE_TAXABLE,
            ], true)) {
                $closingAmount = $account['openingAmount'] - $account['debtorAmount'] + $account['creditorAmount'];
            } else {
                $this->em->rollback();
                throw new Exception("Account type is not allowed... This shouldn't happen");
            }

            $trialBalanceRecord = new Entity\Statement\TrialBalance\Record(
                $foundAccount,
                $account['openingAmount'],
                $account['debtorAmount'],
                $account['creditorAmount'],
                $closingAmount
            );
            $this->em->persist($trialBalanceRecord);
            $trialBalance->addRecord($trialBalanceRecord);

            if ($foundAccount->hasAnalyticals()) {
                $analyticalAccounts = $this->accountRepository
                    ->compileTrialBalanceForAnalytical($year, $month, $day, $foundAccount);

                foreach ($analyticalAccounts as $analyticalAccount) {
                    if (in_array($foundAccount->getType()->getName(), [
                        AccountType::TYPE_ASSET,
                        AccountType::TYPE_ASSET_AND_LIABILITY,
                        AccountType::TYPE_EXPENSE_TAXABLE,
                        AccountType::TYPE_EXPENSE_NON_TAXABLE,
                    ], true)) {
                        $analyticalClosingAmount = $analyticalAccount['openingAmount'] + $analyticalAccount['debtorAmount'] - $analyticalAccount['creditorAmount'];
                    } elseif (in_array($foundAccount->getType()->getName(), [
                        AccountType::TYPE_LIABILITY,
                        AccountType::TYPE_REVENUE_TAXABLE,
                    ], true)) {
                        $analyticalClosingAmount = $analyticalAccount['openingAmount'] - $analyticalAccount['debtorAmount'] + $analyticalAccount['creditorAmount'];
                    } else {
                        $this->em->rollback();
                        throw new Exception("Account type is not allowed... This shouldn't happen");
                    }

                    $trialBalanceRecord = new Entity\Statement\TrialBalance\Record(
                        $foundAccount,
                        $analyticalAccount['openingAmount'],
                        $analyticalAccount['debtorAmount'],
                        $analyticalAccount['creditorAmount'],
                        $analyticalClosingAmount,
                        $this->analyticalRepository->find($analyticalAccount['analyticalId']),
                    );

                    $this->em->persist($trialBalanceRecord);
                    $trialBalance->addRecord($trialBalanceRecord);
                }
            }
        }

        $this->em->flush();
        $this->em->commit();
    }
}
