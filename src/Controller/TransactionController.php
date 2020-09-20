<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\TransactionRepository;
use App\Service;
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
        TransactionRepository $transactionRepository,
        Form\TransactionForm $transactionForm
    ): Response {
        $transactions = $transactionRepository->findBy([], ["taxableSupplyDate" => "DESC"]);

        return $this->render('page.transactions.html.twig', [
            'transactions' => $transactions,
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

    /**
     * @Route("/api/transactions/list", name="api_transactions_list")
     * @param Request $request
     * @param TransactionRepository $transactionRepository
     * @param Service\Serializer $serializer
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function apiList(
        Request $request,
        TransactionRepository $transactionRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $page = (int)$request->query->get('page');
        $page = $page < 1 ? 1 : $page;
        $limit = (int)$request->query->get('limit');
        $limit = $limit < 1 ? 50 : $limit;

        $transactions = [
            "data" => $transactionRepository->findBy([], ["taxableSupplyDate" => "DESC"], $limit, ($page - 1) * $limit),
            "pages" => [
                "current" => $page,
                "total" => ceil($transactionRepository->count([]) / $limit),
            ],
        ];

        return $this->json(
            $serializer->normalize(
                $transactions,
                'json',
                ['groups' => 'transactions']
            )
        );
    }

    /**
     * @Route("/api/transactions/detail", name="api_transaction_detail")
     * @param Request $request
     * @param TransactionRepository $transactionRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function detail(
        Request $request,
        TransactionRepository $transactionRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $id = (int)$request->query->get('id');
        $transaction = $transactionRepository->find($id);

        return $this->json(
            $serializer->normalize(
                $transaction,
                'json',
                ['groups' => 'transactions']
            )
        );
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

        return $this->redirectToRoute('transactions');
    }
}
