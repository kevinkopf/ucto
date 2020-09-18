<?php

namespace App\Controller;

use App\Handler\Statement\TrialBalanceCompiler;
use App\Preparer\AccountStatementPreparer;
use App\Preparer\TrialBalancePreparer;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatementController extends AbstractController
{
    /**
     * @Route("/obratova_predvaha", name="trial_balance")
     */
    public function trialBalance(TrialBalancePreparer $trialBalancePreparer): Response
    {
        return $this->render('page.trialBalance.html.twig', [
            'trialBalance' => $trialBalancePreparer->prepare(),
        ]);
    }

    /**
     * @Route("/api/trial_balance/compile", name="api_trial_balance_compile")
     * @param Request $request
     * @param TrialBalanceCompiler $trialBalanceCompiler
     * @return JsonResponse
     */
    public function trialBalanceCompile(Request $request, TrialBalanceCompiler $trialBalanceCompiler): JsonResponse
    {
        try {
            $trialBalanceCompiler->compile(json_decode($request->getContent(), true));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        return $this->json([], 200);
    }

    /**
     * @Route("/income_statement", name="income_statement")
     */
    public function incomeStatement(): Response
    {
        return $this->render('page.incomeStatement.html.twig');
    }

    /**
     * @Route("/account_statement/{account}/{year}")
     */
    public function accountStatement(
        Request $request,
        AccountStatementPreparer $accountStatementPreparer,
        AccountRepository $accountRepository
    ) {
        return $this->render('page.accountStatement.html.twig', [
            'account' => $accountRepository->findOneBy(['numeral' => $request->attributes->get('account')]),
            'records' => $accountStatementPreparer->prepare($request)
        ]);
    }
}
