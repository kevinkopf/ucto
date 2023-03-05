<?php

namespace App\Form;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TransactionForm
{
    public function __construct(
        private UrlGeneratorInterface $router
    )
    {
    }

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
            'submitUrl' => $this->router->generate('api_transactions_create'),
        ];
    }
}
