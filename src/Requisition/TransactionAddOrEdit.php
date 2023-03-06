<?php

namespace App\Requisition;

use App\Transactions\Entity\TransactionRow;
use DateTime;

class TransactionAddOrEdit
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var DateTime
     */
    public $taxableSupplyDate;

    /**
     * @var string
     */
    public $documentNumber;

    /**
     * @var \App\Contacts\Entity\Contact
     */
    public $contact;

    /**
     * @var string
     */
    public $description;

    /**
     * @var TransactionRow[]
     */
    public $rows;
}
