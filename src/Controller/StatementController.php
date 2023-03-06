<?php

namespace App\Controller;

use App\Accounts\Repository\AccountRepository;
use App\Handler\Statement\TrialBalanceCompiler;
use App\Preparer\AccountStatementPreparer;
use App\Preparer\TrialBalanceListPreparer;
use App\Preparer\TrialBalancePreparer;
use App\Repository\Statement\TrialBalanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatementController extends AbstractController
{
    public function trialBalance(?int $id, TrialBalancePreparer $trialBalancePreparer): Response
    {
        return $this->render('page.trialBalance.html.twig', [
            'trialBalance' => $trialBalancePreparer->prepare($id),
        ]);
    }

    public function trialBalanceCompile(Request $request, TrialBalanceCompiler $trialBalanceCompiler): JsonResponse
    {
        try {
            $trialBalanceCompiler->compile(json_decode($request->getContent(), true));
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        return $this->json([], 200);
    }

    public function trialBalanceList(TrialBalanceListPreparer $trialBalanceListPreparer): JsonResponse
    {
        return $this->json($trialBalanceListPreparer->prepare(), 200);
    }

    public function trialBalanceRemove(
        ?int $id,
        EntityManagerInterface $em,
        TrialBalanceRepository $trialBalanceRepository
    ): JsonResponse {
        if (!$id) {
            return $this->json(['error' => 'No id provided'], 400);
        }

        try {
            $trialBalance = $trialBalanceRepository->find($id);

            if ($trialBalance) {
                $em->remove($trialBalance);
                $em->flush();
            }
        } catch (Exception $e) {
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

    public function accountStatement(
        Request $request,
        AccountStatementPreparer $accountStatementPreparer,
        AccountRepository $accountRepository
    ): Response {
        return $this->render('page.accountStatement.html.twig', [
            'account' => $accountRepository->findOneBy(['numeral' => $request->attributes->get('account') ?: '221']),
            'records' => $accountStatementPreparer->prepare($request)
        ]);
    }
}
