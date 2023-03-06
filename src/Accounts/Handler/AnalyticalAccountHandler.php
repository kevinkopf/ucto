<?php

namespace App\Accounts\Handler;

use App\Accounts\Entity\Account;
use App\Accounts\Entity\AnalyticalAccount;
use App\Accounts\Exception\AccountAlreadyExistsException;
use App\Accounts\Exception\AccountNotFoundException;
use App\Accounts\Repository\AccountRepository;
use App\Accounts\Repository\AnalyticalAccountRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AnalyticalAccountHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AccountRepository $accountRepository,
        private AnalyticalAccountRepository $analyticalAccountRepository,
        private FormService $formService
    )
    {
    }

    public function handle(Request $request): void
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_account');

        if (!$payload['account']['id']) {
            throw new BadRequestHttpException();
        }

        $account = $this->accountRepository->find($payload['account']['id']);

        if (!$account) {
            throw new AccountNotFoundException();
        }

        if (!$payload['id']) {
            $analyticalAccount = $this->create($payload, $account);
        } else {
            $analyticalAccount = $this->update($payload, $account);
        }

        $this->entityManager->persist($analyticalAccount);
        $this->entityManager->flush();
    }

    private function create(array $payload, Account $account): AnalyticalAccount
    {
        if ($this->analyticalAccountRepository->findOneBy([
            'account' => $account,
            'numeral' => $payload['numeral'],
        ])) {
            throw new AccountAlreadyExistsException("Analytical account for {$account->getNumeral()} - {$account->getName()} with numeral {$payload['numeral']} already exists");
        }

        return new AnalyticalAccount(
            $payload['name'],
            $payload['numeral'],
            $account
        );
    }

    private function update(array $payload, Account $account): AnalyticalAccount
    {
        if (!$payload['id']) {
            throw new BadRequestHttpException();
        }

        $analyticalAccount = $this->analyticalAccountRepository->find($payload['id']);

        if (!$analyticalAccount) {
            throw new AccountNotFoundException();
        }

        $analyticalAccount
            ->update(
                $payload['name'],
                $payload['numeral'],
                $account
            );

        return $analyticalAccount;
    }
}