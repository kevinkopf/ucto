<?php

namespace App\Preparer;

use App\Entity\Statement\TrialBalance\Record;
use App\Repository\Statement\TrialBalanceRepository;

class TrialBalanceListPreparer
{
    private TrialBalanceRepository $trialBalanceRepository;

    public function __construct(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    public function prepare(): array
    {
        $statements = $this->trialBalanceRepository->findBy([], ['compiledToDate' => 'DESC', 'id' => 'DESC']);

        $result = [];

        foreach ($statements as $statement) {
            $result[] = [
                'id' => $statement->getId(),
                'date' => $statement->getCompiledToDate()->format('d.m.Y'),
                'compiledDate' => $statement->getCompiledAt()->format('d.m.Y H:i:s'),
            ];
        }

        return $result;
    }
}
