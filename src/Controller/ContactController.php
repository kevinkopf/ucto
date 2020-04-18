<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/api/contacts/search", name="api_contacts_search")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ) {
        $name = (string) $request->query->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }
}
