<?php

namespace App\Form;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactForm
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
                'address' => "",
                'phone' => "",
                'email' => "",
                'registrationNumber' => "",
                'isVatPayer' => false,
                'vatNumberPrefix' => "",
                'vatNumber' => "",
            ],
            'submitUrl' => $this->router->generate('contacts_api_create'),
        ];
    }
}
