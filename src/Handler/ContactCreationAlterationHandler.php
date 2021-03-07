<?php

namespace App\Handler;

use App\Entity;
use App\Repository\ContactRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class ContactCreationAlterationHandler
{
    private EntityManagerInterface $entityManager;
    private ContactRepository $contactRepository;
    private FormService $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository,
        FormService $formService
    ) {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->formService = $formService;
    }

    public function handle(Request $request)
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_contact');

//        dd($payload);

        if (!$payload['id']) {
            $contact = $this->create($payload);
        } else {
            $contact = $this->update($payload);
        }

        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    private function create(array $payload): Entity\Contact
    {
        return new Entity\Contact(
            $payload['name'],
            $payload['address'],
            $payload['registrationNumber'],
            $payload['isVatPayer'],
            $payload['isVatPayer'] ? $payload['vatNumberPrefix'] : null,
            $payload['isVatPayer'] ? $payload['vatNumber'] : null,
            $payload['phone'],
            $payload['email']
        );
    }

    private function update(array $payload): Entity\Contact
    {
        $contact = $this->contactRepository->find($payload['id']);

        if (!$contact) {
            throw new BadRequestException("Non-existant contact.");
        }

        return $contact->update(
            $payload['name'],
            $payload['address'],
            $payload['registrationNumber'],
            $payload['isVatPayer'],
            $payload['isVatPayer'] ? $payload['vatNumberPrefix'] : null,
            $payload['isVatPayer'] ? $payload['vatNumber'] : null,
            $payload['phone'],
            $payload['email']
        );
    }
}
