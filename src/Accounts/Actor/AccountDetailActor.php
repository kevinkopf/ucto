<?php

namespace App\Accounts\Actor;

use App\Accounts\Repository\AccountRepository;

class AccountDetailActor extends AbstractAccountDetailActor
{
    public function __construct(
        AccountRepository $accountRepository
    )
    {
        parent::__construct($accountRepository);
    }
}