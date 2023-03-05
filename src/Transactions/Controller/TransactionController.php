<?php

namespace App\Transactions\Controller;

use App\Transactions\Actor\TransactionDetailActor;
use App\Transactions\Actor\TransactionsListActor;
use App\Transactions\Exception\TransactionNotFoundException;
use App\Transactions\Handler\TransactionHandler;
use App\Transactions\Handler\TransactionRemoveHandler;
use App\Transactions\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/transactions', name: 'api_transactions_')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'listing', methods: ["POST"])]
    public function listing(Request $request, TransactionsListActor $transactionsListActor): JsonResponse
    {
        return $this->json($transactionsListActor->prepare($request), 200);
    }

    #[Route('/detail', name: 'detail', methods: ["POST"])]
    public function detail(Request $request, TransactionDetailActor $transactionDetailActor): JsonResponse
    {
        try {
            return $this->json($transactionDetailActor->prepare($request));
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (TransactionNotFoundException $e) {
            return $this->json([], 404);
        }
    }

    #[Route('/create', name: 'create', methods: ["POST"])]
    public function create(
        Request            $request,
        TransactionHandler $handler,
    ): JsonResponse
    {
        $handler->handle($request);
        return $this->json([], 200);
    }

    #[Route('/remove', name: 'remove', methods: ["POST"])]
    public function remove(
        Request $request,
        TransactionRemoveHandler $handler,
    ): JsonResponse {
        try {
            $handler->handle($request);

            return $this->json([], 204);
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (TransactionNotFoundException $e) {
            return $this->json([], 404);
        }
    }
}