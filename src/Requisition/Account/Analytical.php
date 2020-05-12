<?php

namespace App\Requisition\Account;

use App\Entity;

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
     * @var Entity\Account
     */
    public $account;
}
