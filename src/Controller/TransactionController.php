<?php

namespace App\Controller;

use App\Actor\TransactionDetailActor;
use App\Actor\TransactionsListActor;
use App\Form;
use App\Handler\TransactionCreationAlterationHandler;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends AbstractController
{
    public function list(
        Form\TransactionForm $transactionForm
    ): Response {
        return $this->render('page.transactions.html.twig', [
            'forms' => [
                'transaction' => $transactionForm->stage(),
            ],
        ]);
    }

    public function submitForm(Request $request, TransactionCreationAlterationHandler $handler): RedirectResponse
    {
        $handler->handle($request);

        return $this->redirectToRoute('transactions_list');
    }

    public function apiList(Request $request, TransactionsListActor $transactionsListActor): JsonResponse
    {
        return $this->json($transactionsListActor->prepare($request));
    }

    public function apiDetail(Request $request, TransactionDetailActor $transactionDetailActor): JsonResponse
    {
        return $this->json($transactionDetailActor->prepare($request));
    }

    public function apiRemove(
        Request $request,
        TransactionRepository $transactionRepository,
        EntityManagerInterface $em
    ): RedirectResponse {
        $id = (int)$request->query->get('id');
        $transaction = $transactionRepository->find($id);
        $em->remove($transaction);
        $em->flush();

        return $this->redirectToRoute('transactions_list');
    }
}
