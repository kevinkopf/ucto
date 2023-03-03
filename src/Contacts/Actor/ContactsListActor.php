<?php

namespace App\Contacts\Actor;

use App\Contacts\Repository\ContactRepository;
use App\Contacts\Repository\ContactSearchCriteria;
use Symfony\Component\HttpFoundation\Request;

class ContactsListActor
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function prepare(Request $request): array
    {
        $page = (int)$request->request->get('page');
        $page = $page < 1 ? 1 : $page;
        $limit = (int)$request->request->get('limit');
        $limit = $limit < 10 ? 50 : $limit;

        $searchCriteria = new ContactSearchCriteria(
            $limit,
            ($page - 1) * $limit,
            $request->request->get('search'),
            $request->request->get('search'),
            $request->request->get('search'),
            $request->request->get('search'),
        );

        $contacts = [
            'data' => [],
            'pages' => [
                'current' => $page,
                'total' => ceil($this->contactRepository->count([]) / $limit),
            ],
        ];

        $preparedContacts = $this->contactRepository->findByCriteria($searchCriteria);

        foreach ($preparedContacts as $contact) {
            $contacts['data'][] = [
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                'address' => $contact->getAddress(),
                'registrationNumber' => $contact->getRegistrationNumber(),
                'vatPayer' => $contact->isVatPayer(),
                'vatNumberPrefix' => $contact->getVatNumberPrefix(),
                'vatNumber' => $contact->getVatNumber(),
                'phone' => $contact->getPhone(),
                'email' => $contact->getEmail(),
                'transactionsCount' => count($contact->getTransactions()),
            ];
        }

        return $contacts;
    }
}
