<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="transaction")
     */
    public function index(TransactionRepository $transactionRepository)
    {
        $transactions = $transactionRepository->findAll();

        return $this->render('page.transactions.html.twig', [
            'transactions' => $transactions,
        ]);
    }
}
