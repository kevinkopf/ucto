<?php

namespace App\Requisition;

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
     * @var \App\Contacts\Entity\Contact
     */
    public $contact;

    /**
     * @var string
     */
    public $description;

    /**
     * @var \App\Transactions\Entity\TransactionRow[]
     */
    public $rows;
}
