<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ContactController extends AbstractController
{
    /**
     * @Route("/api/contacts/search", name="api_contacts_search")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function index(Request $request, ContactRepository $contactRepository)
    {
        $name = (string) $request->query->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer(
            [ new ObjectNormalizer($classMetadataFactory) ],
            [ new JsonEncoder() ]
        );

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }
}
