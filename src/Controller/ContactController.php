<?php

namespace App\Controller;

use App\Actor\ContactDetailActor;
use App\Actor\ContactsListActor;
use App\Form;
use App\Handler\ContactCreationAlterationHandler;
use App\Repository\ContactRepository;
use App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function contacts(
        Form\ContactForm $contactForm
    ): Response {
        return $this->render('page.contacts.html.twig', [
            'forms' => [
                'contact' => $contactForm->stage(),
            ],
        ]);
    }

    public function apiCreate(Request $request, ContactCreationAlterationHandler $handler): RedirectResponse
    {
        $handler->handle($request);

        return $this->redirectToRoute('contacts_list');
    }

    public function apiSearch(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $name = (string)$request->request->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }

    public function list(
        Request $request,
        ContactsListActor $actor
    ): JsonResponse {
        return $this->json($actor->prepare($request));
    }

    public function details(Request $request, ContactDetailActor $actor): JsonResponse
    {
        return $this->json($actor->prepare($request));
    }

    public function remove(
        Request $request,
        ContactRepository $contactRepository,
        EntityManagerInterface $em
    ): RedirectResponse {
        $id = (int)$request->query->get('id');
        $contact = $contactRepository->find($id);

        if (count($contact->getTransactions()) === 0) {
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contacts_list');
    }
}
