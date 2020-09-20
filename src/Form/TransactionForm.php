<?php

namespace App\Form;

use DateTime;

class TransactionForm
{
    public function stage(): array
    {
        return [
            'payload' => [
                'id' => 0,
                'taxableSupplyDate' => "",
                'documentNumber' => "",
                'contact' => "",
                'description' => "",
                'rows' => [
                    [
                        'description' => "",
                        'amount' => 0,
                        'debtorsAccount' => "",
                        'creditorsAccount' => "",
                    ],
                ],
            ],
            'submitUrl' => null,
        ];
    }
}
