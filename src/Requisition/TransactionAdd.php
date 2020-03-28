<?php

namespace App\Requisition;

use App\Entity\Contact;
use DateTime;
use Symfony\Component\Validator\Constraints;

class TransactionAdd
{
    /**
     * @Constraints\Date()
     * @var DateTime
     */
    public DateTime $taxableSupplyDate;

    /**
     * @var Contact
     */
    public Contact $contact;

    /**
     * @var string
     */
    public string $description;


}
