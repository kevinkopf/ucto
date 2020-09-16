<?php

namespace App\Preparer;

use App\Entity\Account;
use App\Entity\Statement\TrialBalanceRecord;
use App\Repository\Statement\TrialBalanceRepository;

class TrialBalancePreparer
{
    private TrialBalanceRepository $trialBalanceRepository;

    public function __construct(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    public function prepare(): array
    {
        $trialBalance = $this->trialBalanceRepository->findOneBy([], ['compiledToDate' => 'DESC', 'id' => 'DESC']);

        if (!$trialBalance) {
            return [];
        }

        $result = [
            'date' => $trialBalance->getCompiledToDate(),
            'records' => [
                Account\Type::TYPE_ASSET => [],
                Account\Type::TYPE_ASSET_AND_LIABILITY => [],
                Account\Type::TYPE_LIABILITY => [],
                Account\Type::TYPE_EXPENSE_TAXABLE => [],
                Account\Type::TYPE_EXPENSE_NON_TAXABLE => [],
                Account\Type::TYPE_REVENUE_TAXABLE => [],
            ],
        ];

        /** @var TrialBalanceRecord $record */
        foreach ($trialBalance->getRecords() as $record) {
            $result['records'][$record->getAccount()->getType()->getName()][] = $record;
        }

        return $result;
    }
}
