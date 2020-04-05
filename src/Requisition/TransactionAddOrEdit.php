<?php

namespace App\Requisition;

use App\Entity;
use DateTime;
use Symfony\Component\Validator\Constraints;

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
