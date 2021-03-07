<?php

namespace App\Actor;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactDetailActor
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function prepare(Request $request): array
    {
        $contact = $this->contactRepository->find($request->attributes->get('id'));

        if (!$contact) {
            throw new NotFoundHttpException('Contact does not exist');
        }

        return [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'address' => $contact->getAddress(),
            'registrationNumber' => $contact->getRegistrationNumber(),
            'phone' => $contact->getPhone(),
            'email' => $contact->getEmail(),
            'vatPayer' => $contact->isVatPayer(),
            'vatNumberPrefix' => $contact->getVatNumberPrefix(),
            'vatNumber' => $contact->getVatNumber(),
        ];
    }
}
