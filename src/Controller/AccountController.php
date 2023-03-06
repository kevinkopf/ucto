<?php

namespace App\Controller;

use App\Accounts\Repository\AccountRepository;
use App\Form;
use App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
