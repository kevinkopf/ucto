<?php

namespace App\Requisition;

use App\Entity;

class Contact
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $registrationNumber;

    /**
     * @var bool
     */
    public $isVatPayer;

    /**
     * @var string
     */
    public $vatNumberPrefix;

    /**
     * @var string
     */
    public $vatNumber;
}
