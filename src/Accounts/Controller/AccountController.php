<?php

namespace App\Accounts\Controller;

use App\Accounts\Actor\AccountDetailActor;
use App\Accounts\Actor\AccountsListActor;
use App\Accounts\Actor\AnalyticalAccountDetailActor;
use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Handler\AnalyticalAccountCreationAlterationHandler;
use App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/accounts', name: 'api_accounts_')]
class AccountController extends AbstractController
{
    #[Route('/analytical/create', name: 'analytical_create', methods: ["POST"])]
    public function createAnalytical(
        Request $request,
        AnalyticalAccountCreationAlterationHandler $handler
    ): JsonResponse
    {
        $handler->handle($request);
        return $this->json([], 204);
    }

    #[Route('/', name: 'listing', methods: ["POST"])]
    public function listing(
        Request $request,
        AccountsListActor $actor,
    ): JsonResponse
    {
        return $this->json($actor->prepare($request), 200);
    }

    #[Route('/search', name: 'search', methods: ["POST"])]
    public function search(
        Request $request,
        AccountDetailActor $actor
    ): JsonResponse
    {
        try {
            return $this->json($actor->search($request), 200);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        }
    }

    #[Route('/analytical', name: 'analytical_listing', methods: ["POST"])]
    public function listingAnalytical(
        Request $request,
        AccountRepository $accountRepository,
        AnalyticalAccountRepository $analyticalRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $account = $accountRepository->find($request->request->get('account'));

        if (!$account) {
            throw new NotFoundHttpException('Account was not found');
        }

        $nameOrNumeral = (string)$request->request->get('search');
        $analyticalAccounts = $analyticalRepository->findSimilarByNameOrNumeral($account, $nameOrNumeral);

        return $this->json($serializer->normalize($analyticalAccounts, 'json', ['groups' => 'accounts']));
    }

    #[Route('/analytical/search', name: 'analytical_search', methods: ["POST"])]
    public function searchAnalytical(
        Request $request,
        AnalyticalAccountDetailActor $actor
    ): JsonResponse
    {
        try {
            return $this->json($actor->search($request), 200);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        }
    }

    #[Route('/analytical/remove', name: 'analytical_remove', methods: ["POST"])]
    public function remove(
        Request $request,
        AnalyticalAccountRepository $analyticalRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $id = (int)$request->query->get('id');

        if (!$id) {
            return $this->json([], 400);
        }

        $analyticalAccount = $analyticalRepository->find($id);

        if (!$analyticalAccount) {
            return $this->json([], 404);
        }

        $em->remove($analyticalAccount);
        $em->flush();

        return $this->json([], 204);
    }
}
