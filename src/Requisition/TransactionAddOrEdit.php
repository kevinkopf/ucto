<?php

namespace App\Requisition;

use App\Entity;
use DateTime;

class TransactionAddOrEdit
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var \DateTime
     */
    public $taxableSupplyDate;

    /**
     * @var string
     */
    public $documentNumber;

    /**
     * @var Entity\Contact
     */
    public $contact;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Entity\Transaction\Row[]
     */
    public $rows;
}
