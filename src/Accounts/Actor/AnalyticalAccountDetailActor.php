<?php

namespace App\Accounts\Actor;

use App\Accounts\Repository\AnalyticalAccountRepository;

class AnalyticalAccountDetailActor extends AbstractAccountDetailActor
{
    public function __construct(
        AnalyticalAccountRepository $accountRepository
    )
    {
        parent::__construct($accountRepository);
    }
}