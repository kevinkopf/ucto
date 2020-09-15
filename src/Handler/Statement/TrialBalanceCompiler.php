<?php

namespace App\Handler\Statement;

use App\Entity;
use App\Entity\Account\Type;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrialBalanceCompiler
{
    private AccountRepository $accountRepository;
    private EntityManagerInterface $em;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $em)
    {
        $this->accountRepository = $accountRepository;
        $this->em = $em;
    }

    public function compile(array $payload)
    {
        $year = (int)$payload['year'];
        $month = (int)$payload['month'];
        $day = (int)$payload['day'];

        $accounts = $this->accountRepository->compileTrialBalance($year, $month, $day);

        $this->em->beginTransaction();

        $trialBalance = new Entity\Statement\TrialBalance(
            (new \DateTime())->setDate($year, $month, $day)
        );
        $this->em->persist($trialBalance);
        $this->em->flush();

        foreach ($accounts as $account) {
            $foundAccount = $this->accountRepository->find($account['id']);

            if(!$foundAccount) {
                $this->em->rollback();
                throw new \Exception('Account not found. This is strange...');
            }

            if(in_array($foundAccount->getType()->getName(), [
                Type::TYPE_ASSET,
                Type::TYPE_ASSET_AND_LIABILITY,
                Type::TYPE_EXPENSE_TAXABLE,
                Type::TYPE_EXPENSE_NON_TAXABLE,
            ], true)) {
                $closingAmount = $account['openingAmount'] + $account['debtorAmount'] - $account['creditorAmount'];
            } elseif (in_array($foundAccount->getType()->getName(), [
                Type::TYPE_LIABILITY,
                Type::TYPE_REVENUE_TAXABLE,
            ], true)) {
                $closingAmount = $account['openingAmount'] - $account['debtorAmount'] + $account['creditorAmount'];
            } else {
                $this->em->rollback();
                throw new \Exception("Account type is not allowed... This shouldn't happen");
            }

            $trialBalanceRecord = new Entity\Statement\TrialBalanceRecord(
                $foundAccount,
                $account['openingAmount'],
                $account['debtorAmount'],
                $account['creditorAmount'],
                $closingAmount
            );
            $this->em->persist($trialBalanceRecord);
            $trialBalance->addRecord($trialBalanceRecord);
        }

        $this->em->flush();
        $this->em->commit();
    }
}
