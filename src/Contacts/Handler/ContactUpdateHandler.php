<?php

namespace App\Contacts\Handler;

use App\Contacts\Entity\Contact;
use App\Contacts\Exception\ContactNotFoundException;
use App\Contacts\Repository\ContactRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContactUpdateHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ContactRepository $contactRepository,
        private FormService $formService,
    ) {
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_contact');

        if (!$payload['id']) {
            throw new BadRequestHttpException();
        }

        $contact = $this->contactRepository->find($payload['id']);

        if (!$contact) {
            throw new ContactNotFoundException();
        }

        $contact->update(
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
