<?php

namespace App\Accounts\Controller;

use App\Accounts\Actor\AccountDetailActor;
use App\Accounts\Actor\AccountsListActor;
use App\Accounts\Actor\AnalyticalAccountDetailActor;
use App\Accounts\Actor\AnalyticalAccountListActor;
use App\Accounts\Exception\AccountAlreadyExistsException;
use App\Accounts\Exception\AccountNotFoundException;
use App\Accounts\Handler\AnalyticalAccountHandler;
use App\Accounts\Handler\AnalyticalAccountRemoveHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/accounts', name: 'api_accounts_')]
class AccountController extends AbstractController
{
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
        AnalyticalAccountListActor $analyticalAccountListActor,
    ): JsonResponse {
        try {
            return $this->json($analyticalAccountListActor->prepare($request), 200);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (AccountNotFoundException $e) {
            return $this->json([], 404);
        }
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

    #[Route('/analytical/create', name: 'analytical_create', methods: ["POST"])]
    public function createAnalytical(
        Request $request,
        AnalyticalAccountHandler $handler
    ): JsonResponse
    {
        try {
            $handler->handle($request);
            return $this->json([], 204);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (AccountNotFoundException $e) {
            return $this->json([], 400);
        } catch (AccountAlreadyExistsException $e) {
            return $this->json([], 400);
        }
    }

    #[Route('/analytical/remove', name: 'analytical_remove', methods: ["POST"])]
    public function remove(
        Request $request,
        AnalyticalAccountRemoveHandler $handler,
    ): JsonResponse
    {
        try {
            $handler->handle($request);
            return $this->json([], 204);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (AccountNotFoundException $e) {
            return $this->json([], 404);
        }
    }
}
