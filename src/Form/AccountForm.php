<?php

namespace App\Form;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountForm
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function stage(): array
    {
        return [
            'payload' => [
                'id' => 0,
                'name' => "",
                'numeral' => "",
                'account' => "",
            ],
            'submitUrl' => $this->router->generate('api_accounts_analytical_create'),
        ];
    }
}
