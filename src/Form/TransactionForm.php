<?php

namespace App\Form;

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
                        'debtorsAnalyticalAccount' => "",
                        'creditorsAccount' => "",
                        'creditorsAnalyticalAccount' => "",
                    ],
                ],
            ],
            'submitUrl' => null,
        ];
    }
}
