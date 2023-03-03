<?php

namespace App\Contacts\Handler;

use App\Contacts\Entity\Contact;
use App\Contacts\Repository\ContactRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class ContactCreateHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormService $formService
    ) {
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_contact');

        $contact = new Contact(
            $payload['name'],
            $payload['address'],
            $payload['registrationNumber'],
            $payload['isVatPayer'],
            $payload['vatNumberPrefix'],
            $payload['vatNumber'],
            $payload['phone'],
            $payload['email']
        );

        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }
}
