<?php

namespace App\Accounts\Handler;

use App\Accounts\Exception\AccountNotFoundException;
use App\Accounts\Repository\AnalyticalAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AnalyticalAccountRemoveHandler
{
    public function __construct(
        private AnalyticalAccountRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function handle(Request $request): void
    {
        $id = (int) $request->request->get('id');

        if (!$id) {
            throw new BadRequestHttpException();
        }

        $account = $this->repository->find($id);

        if (!$account) {
            throw new AccountNotFoundException();
        }

        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }
}