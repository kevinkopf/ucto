<?php

namespace App\Preparer;

use App\Entity\Account;
use App\Entity\Statement\TrialBalance\Record;
use App\Repository\Statement\TrialBalanceRepository;

class TrialBalancePreparer
{
    private TrialBalanceRepository $trialBalanceRepository;

    public function __construct(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    public function prepare(?int $id): array
    {
        if ($id) {
            $trialBalance = $this->trialBalanceRepository->find($id);
        } else {
            $trialBalance = $this->trialBalanceRepository->findOneBy([], ['compiledToDate' => 'DESC', 'id' => 'DESC']);
        }

        $result = [
            'date' => null,
            'compiledDate' => null,
            'records' => [
                Account\Type::TYPE_ASSET => [],
                Account\Type::TYPE_ASSET_AND_LIABILITY => [],
                Account\Type::TYPE_LIABILITY => [],
                Account\Type::TYPE_EXPENSE_TAXABLE => [],
                Account\Type::TYPE_EXPENSE_NON_TAXABLE => [],
                Account\Type::TYPE_REVENUE_TAXABLE => [],
            ],
        ];

        if (!$trialBalance) {
            return $result;
        }

        $result['date'] = $trialBalance->getCompiledToDate()->format('d.m.Y');
        $result['compiledDate'] = $trialBalance->getCompiledAt()->format('d.m.Y H:i:s');

        /** @var Record $record */
        foreach ($trialBalance->getRecords() as $record) {
            if (!$record->getAnalyticalAccount()) {
                $result['records'][$record->getAccount()->getType()->getName()][$record->getAccount()->getId()]['main'] = $record;
            } else {
                $result['records'][$record->getAccount()->getType()->getName()][$record->getAccount()->getId()]['analytical'][] = $record;
            }
        }

        return $result;
    }
}
