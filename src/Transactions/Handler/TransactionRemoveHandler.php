<?php

namespace App\Transactions\Handler;

use App\Transactions\Exception\TransactionNotFoundException;
use App\Transactions\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionRemoveHandler
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function handle(Request $request): void
    {
        $id = (int)$request->request->get('id');

        if (!$id) {
            throw new BadRequestHttpException();
        }

        $transaction = $this->transactionRepository->find($id);

        if (!$transaction) {
            throw new TransactionNotFoundException();
        }

        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
    }
}