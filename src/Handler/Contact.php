<?php

namespace App\Handler;

use App\Entity;
use App\Repository\ContactRepository;
use App\Requisition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Contact
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private ContactRepository $contactRepository;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     * @param ContactRepository $contactRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param Requisition\Contact $requisition
     */
    public function handle(Requisition\Contact $requisition)
    {
        if(!$requisition->id)
        {
            $this->create($requisition);
        }
        else
        {
            $this->update($requisition);
        }
    }

    /**
     * @param Requisition\Contact $requisition
     */
    private function create(Requisition\Contact $requisition): void
    {
        $contact = new Entity\Contact(
            $requisition->name,
            $requisition->address,
            $requisition->registrationNumber,
            $requisition->isVatPayer,
            $requisition->vatNumberPrefix,
            $requisition->vatNumber
        );

        $contact
            ->setPhone($requisition->phone)
            ->setEmail($requisition->email)
            ;

        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    /**
     * @param Requisition\Contact $requisition
     */
    private function update(Requisition\Contact $requisition): void
    {
        $contact = $this->contactRepository->find($requisition->id);
        $contact
            ->setName($requisition->name)
            ->setAddress($requisition->address)
            ->setRegistrationNumber($requisition->registrationNumber)
            ->setVatPayer($requisition->isVatPayer)
            ->setVatNumberPrefix($requisition->vatNumberPrefix)
            ->setVatNumber($requisition->vatNumber)
            ->setPhone($requisition->phone)
            ->setEmail($requisition->email)
        ;

        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }
}
