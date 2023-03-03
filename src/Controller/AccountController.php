<?php

namespace App\Controller;

use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Form;
use App\Handler\AnalyticalAccountCreationAlterationHandler;
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
}
