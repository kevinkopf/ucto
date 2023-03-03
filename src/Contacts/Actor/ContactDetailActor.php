<?php

namespace App\Contacts\Actor;

use App\Contacts\Exception\ContactNotFoundException;
use App\Contacts\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContactDetailActor
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function prepare(Request $request): array
    {
        $id = (int) $request->attributes->get('id');

        if (!$id) {
            throw new BadRequestHttpException();
        }

        $contact = $this->contactRepository->find($id);

        if (!$contact) {
            throw new ContactNotFoundException();
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
