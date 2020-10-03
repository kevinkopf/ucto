<?php

namespace App\Controller;

use App\Actor\TransactionDetailActor;
use App\Actor\TransactionsListActor;
use App\Form;
use App\Handler;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    public function list(
        Request $request,
        TransactionsListActor $transactionsListActor,
        Form\TransactionForm $transactionForm
    ): Response {
        return $this->render('page.transactions.html.twig', [
            'forms' => [
                'transaction' => $transactionForm->stage(),
            ],
        ]);
    }

    public function submitForm(Request $request, Handler\Transaction\AddOrEdit $handler): RedirectResponse
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

    /**
     * @Route("/api/transactions/remove", name="api_transaction_remove")
     * @param Request $request
     * @param TransactionRepository $transactionRepository
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(
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
