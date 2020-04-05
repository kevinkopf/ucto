<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AccountController extends AbstractController
{
    /**
     * @Route("/api/accounts/search", name="api_accounts_search")
     * @param Request $request
     * @param AccountRepository $accountRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(Request $request, AccountRepository $accountRepository)
    {
        $nameOrNumeral = (string) $request->query->get('query');
        $accounts = $accountRepository->findBySimilarByNameOrNumeral($nameOrNumeral);

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer(
            [ new ObjectNormalizer($classMetadataFactory) ],
            [ new JsonEncoder() ]
        );

        return $this->json($serializer->normalize($accounts, 'json', ['groups' => 'accounts']));
    }
}
