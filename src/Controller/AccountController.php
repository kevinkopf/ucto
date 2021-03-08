<?php

namespace App\Controller;

use App\Form;
use App\Handler\AnalyticalAccountCreationAlterationHandler;
use App\Repository\Account\AnalyticalRepository;
use App\Repository\AccountRepository;
use App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountController extends AbstractController
{
    public function list(
        Form\AccountForm $accountForm,
        AccountRepository $accountRepository,
        Service\Serializer $serializer
    ): Response {
        $accounts = $accountRepository->findAll();

        return $this->render('page.accounts.html.twig', [
            'forms' => [
                'account' => $accountForm->stage()
            ],
            'accounts' => $serializer->serialize($accounts, 'json', ['groups' => 'accounts']),
        ]);
    }

    public function createAnalytical(
        Request $request,
        AnalyticalAccountCreationAlterationHandler $handler
    ): RedirectResponse {
        $handler->handle($request);
        return $this->redirectToRoute('accounts_list');
    }

    public function apiSearch(
        Request $request,
        AccountRepository $accountRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $nameOrNumeral = (string)$request->request->get('query');
        $accounts = $accountRepository->findSimilarByNameOrNumeral($nameOrNumeral);

        return $this->json($serializer->normalize($accounts, 'json', ['groups' => 'accounts']));
    }

    public function apiAnalyticalSearch(
        Request $request,
        AccountRepository $accountRepository,
        AnalyticalRepository $analyticalRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $account = $accountRepository->find($request->request->get('account'));

        if (!$account) {
            throw new NotFoundHttpException('Account was not found');
        }

        $nameOrNumeral = (string)$request->request->get('query');
        $analyticalAccounts = $analyticalRepository->findSimilarByNameOrNumeral($account, $nameOrNumeral);

        return $this->json($serializer->normalize($analyticalAccounts, 'json', ['groups' => 'accounts']));
    }

    public function apiRemove(
        Request $request,
        AnalyticalRepository $analyticalRepository,
        EntityManagerInterface $em
    ) {
        $id = (int)$request->query->get('id');
        $analyticalAccount = $analyticalRepository->find($id);

        $em->remove($analyticalAccount);
        $em->flush();

        return $this->redirectToRoute('accounts');
    }
}
