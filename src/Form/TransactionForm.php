<?php

namespace App\Form;

class TransactionForm
{
    public function stage(): array
    {
        return [
            'payload' => [
                'id' => 0,
                'taxableSupplyDate' => null,
                'documentNumber' => "",
                'contact' => null,
                'description' => "",
                'rows' => [
                    [
                        'description' => "",
                        'amount' => "0",
                        'debtorsAccount' => null,
                        'debtorsAnalyticalAccount' => null,
                        'creditorsAccount' => null,
                        'creditorsAnalyticalAccount' => null,
                    ],
                ],
            ],
            'submitUrl' => null,
        ];
    }
}
