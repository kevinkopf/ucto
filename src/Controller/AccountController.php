<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/api/accounts/search", name="api_accounts_search")
     * @param Request $request
     * @param AccountRepository $accountRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function index(
        Request $request,
        AccountRepository $accountRepository,
        Service\Serializer $serializer
    ) {
        $nameOrNumeral = (string) $request->query->get('query');
        $accounts = $accountRepository->findBySimilarByNameOrNumeral($nameOrNumeral);

        return $this->json($serializer->normalize($accounts, 'json', ['groups' => 'accounts']));
    }
}
