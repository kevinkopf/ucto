<?php

namespace App\Requisition\Account;

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
     * @var \App\Accounts\Entity\Account
     */
    public $account;
}
