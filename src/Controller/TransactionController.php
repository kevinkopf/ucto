<?php

namespace App\Controller;

use App\Form;
use App\Handler\TransactionCreationAlterationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
