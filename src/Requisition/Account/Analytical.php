<?php

namespace App\Requisition\Account;

use App\Accounts\Entity\Account;

class Analytical
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string
     */
    public $numeral;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Account
     */
    public $account;
}
