<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Requisition;
use App\Service;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="transactions")
     * @param Request $request
     * @param Handler\Transaction\Add $addTransactionHandler
     * @param Service\VueUtils $vueUtils
     * @param TransactionRepository $transactionRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        Handler\Transaction\Add $addTransactionHandler,
        Service\VueUtils $vueUtils,
        TransactionRepository $transactionRepository
    ) {
        $formRequesition = new Requisition\TransactionAddOrEdit();
        $form = $this->createForm(Form\TransactionType::class, $formRequesition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            dd($formRequesition);
            $addTransactionHandler->handle($formRequesition);
            return $this->redirectToRoute('transactions');
        }

        $transactions = $transactionRepository->findAll();

        return $this->render('page.transactions.html.twig', [
            'transactions' => $transactions,
            'form' => $form->createView(),
            'id' => $vueUtils->encodeProps($form->get('id')),
            'taxableSupplyDate' => $vueUtils->encodeProps($form->get('taxableSupplyDate')),
            'contact' => $vueUtils->encodeProps($form->get('contact')),
            'description' => $vueUtils->encodeProps($form->get('description')),
            'rows' => $vueUtils->encodeProps($form->get('rows')),
        ]);
    }
}
