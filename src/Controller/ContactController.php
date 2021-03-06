<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\ContactRepository;
use App\Requisition;
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
        Request $request,
        Service\VueUtils $vueUtils,
        Handler\Contact $contactHandler,
        ContactRepository $contactRepository
    ): Response {
        $formRequisition = new Requisition\Contact();
        $form = $this->createForm(Form\ContactType::class, $formRequisition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactHandler->handle($formRequisition);

            return $this->redirectToRoute('contacts');
        }

        return $this->render('page.contacts.html.twig', [
            'form' => $form->createView(),
            'id' => $vueUtils->encodeProps($form->get('id')),
            'name' => $vueUtils->encodeProps($form->get('name')),
            'address' => $vueUtils->encodeProps($form->get('address')),
            'phone' => $vueUtils->encodeProps($form->get('phone')),
            'email' => $vueUtils->encodeProps($form->get('email')),
            'registrationNumber' => $vueUtils->encodeProps($form->get('registrationNumber')),
            'isVatPayer' => $vueUtils->encodeProps($form->get('isVatPayer')),
            'vatNumberPrefix' => $vueUtils->encodeProps($form->get('vatNumberPrefix')),
            'vatNumber' => $vueUtils->encodeProps($form->get('vatNumber')),
        ]);
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
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $page = (int)$request->query->get('page');
        $page = $page < 1 ? 1 : $page;
        $limit = (int)$request->query->get('limit');
        $limit = $limit < 1 ? 50 : $limit;

        $contacts = [
            "data" => $contactRepository->findBy([], ["name" => "ASC"], $limit, ($page - 1) * $limit),
            "pages" => [
                "current" => $page,
                "total" => ceil($contactRepository->count([]) / $limit),
            ],
        ];

        return $this->json(
            $serializer->normalize(
                $contacts,
                'json',
                ['groups' => 'contacts']
            )
        );
    }

    public function details(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $id = (int)$request->query->get('id');
        $contact = $contactRepository->find($id);

        return $this->json(
            $serializer->normalize(
                $contact,
                'json',
                ['groups' => 'contacts']
            )
        );
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

        return $this->redirectToRoute('contacts');
    }
}
