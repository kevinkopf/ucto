<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/kontakty", name="contacts")
     * @param ContactRepository $contactRepository
     * @return Response
     */
    public function contacts(
        ContactRepository $contactRepository
    ): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('page.contacts.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/api/contacts/search", name="api_contacts_search")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function apiContactsSearch(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse
    {
        $name = (string) $request->query->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }
}
