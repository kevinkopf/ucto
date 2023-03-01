<?php

namespace App\Handler;

use App\Entity;
use App\Repository\Account\AnalyticalRepository;
use App\Repository\AccountRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AnalyticalAccountCreationAlterationHandler
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private AnalyticalRepository $analyticalAccountRepository;
    private FormService $formService;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        AccountRepository $accountRepository,
        AnalyticalRepository $analyticalAccountRepository,
        FormService $formService
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
        $this->analyticalAccountRepository = $analyticalAccountRepository;
        $this->formService = $formService;
    }

    public function handle(Request $request)
    {
        $payload = $this->formService->decodeAndSanitizePayload($request, 'form_account');
        $account = $this->accountRepository->find($payload['account']['id']);

        if (!$account) {
            throw new BadRequestException("Wrong account was supplied with the form.");
        }

        if (!$payload['id']) {
            $analyticalAccount = $this->create($payload, $account);
        } else {
            $analyticalAccount = $this->update($payload);
        }

        $this->entityManager->persist($analyticalAccount);
        $this->entityManager->flush();
    }

    private function create(array $payload, Entity\Account $account): Entity\Account\Analytical
    {
        if ($this->analyticalAccountRepository->findOneBy([
            'account' => $account,
            'numeral' => $payload['numeral'],
        ])) {
            throw new BadRequestException("Analytical account for {$account->getNumeral()} - {$account->getName()} with numeral {$payload['numeral']} already exists");
        }

        return new Entity\Account\Analytical(
            $payload['name'],
            $payload['numeral'],
            $account
        );
    }

    private function update(array $payload): Entity\Account\Analytical
    {
        $analyticalAccount = $this->analyticalAccountRepository->find($payload['id']);

        if (!$analyticalAccount) {
            throw new BadRequestException("Non-existant analytical account.");
        }

        return $analyticalAccount
            ->setName($payload['name'])
            ->setNumeral($payload['numeral'])
            ;
    }
}
