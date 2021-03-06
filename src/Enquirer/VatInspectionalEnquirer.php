<?php

namespace App\Enquirer;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class VatInspectionalEnquirer
{
    private EntityManagerInterface $em;
    private Account $vatAccount;
    private Account $reverseChargeAccount;

    public function __construct(EntityManagerInterface $em, AccountRepository $accountRepository)
    {
        $this->em = $em;
        $this->vatAccount = $accountRepository->findOneBy(['numeral' => '343']);
        $this->reverseChargeAccount = $accountRepository->findOneBy(['numeral' => '349']);
    }

    public function selectA1($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix = :vatPrefix')
            ->andWhere('tr.debtorsAccount = :reverseChargeAccount')
            ->andWhere('tr.creditorsAccount = :vatAccount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->setParameter('vatAccount', $this->vatAccount)
            ->getQuery()
            ->getResult()
        ;
    }

    public function selectA2($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix != :vatPrefix')
            ->andWhere('tr.debtorsAccount = :reverseChargeAccount')
            ->andWhere('tr.creditorsAccount = :vatAccount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->setParameter('vatAccount', $this->vatAccount)
            ->getQuery()
            ->getResult()
        ;
    }

    public function selectA3($year, $month)
    {
        # TODO: This is a stub
    }

    public function selectA4($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix = :vatPrefix')
            ->andWhere('tr.debtorsAccount != :reverseChargeAccount')
            ->andWhere('tr.creditorsAccount = :vatAccount')
            ->andWhere('tr.amount >= :amount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->setParameter('vatAccount', $this->vatAccount)
            ->setParameter('amount', 173554)
            ->getQuery()
            ->getResult()
        ;
    }

    public function selectA5($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix = :vatPrefix')
            ->andWhere('tr.debtorsAccount != :reverseChargeAccount')
            ->andWhere('tr.creditorsAccount = :vatAccount')
            ->andWhere('tr.amount < :amount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->setParameter('vatAccount', $this->vatAccount)
            ->setParameter('amount', 173554)
            ->getQuery()
            ->getResult()
        ;
    }

    public function selectB1($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix != :vatPrefix')
            ->andWhere('tr.debtorsAccount = :vatAccount')
            ->andWhere('tr.creditorsAccount = :reverseChargeAccount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('vatAccount', $this->vatAccount)
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->getQuery()
            ->getResult()
        ;
    }

    public function selectB2($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix = :vatPrefix')
            ->andWhere('tr.amount >= :amount')
            ->andWhere('tr.debtorsAccount = :vatAccount')
            ->andWhere('tr.creditorsAccount != :reverseChargeAccount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('amount', 173554)
            ->setParameter('vatAccount', $this->vatAccount)
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->getQuery()
            ->getResult();
    }

    public function selectB3($year, $month): array
    {
        [$startDate, $endDate] = $this->buildDateParameters($year, $month);

        return $this->buildBasicQuery()
            ->andWhere('c.vatNumberPrefix = :vatPrefix')
            ->andWhere('tr.amount < :amount')
            ->andWhere('tr.debtorsAccount = :vatAccount')
            ->andWhere('tr.creditorsAccount != :reverseChargeAccount')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('vatPrefix', 'CZ')
            ->setParameter('amount', 173554)
            ->setParameter('vatAccount', $this->vatAccount)
            ->setParameter('reverseChargeAccount', $this->reverseChargeAccount)
            ->getQuery()
            ->getResult()
        ;
    }

    private function buildDateParameters($year, $month): array
    {
        $startDate = (new \DateTime())
            ->setDate($year, $month, 1)
            ->setTime(0, 0, 0);

        return [
            $startDate,
            (new \DateTime())
                ->setDate($startDate->format('Y'), $startDate->format('n'), $startDate->format('t'))
                ->setTime(23, 59, 59)
        ];
    }

    private function buildBasicQuery(): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select('tr')
            ->from(Transaction\Row::class, 'tr')
            ->leftJoin('tr.transaction', 't')
            ->leftJoin('t.contact', 'c')
            ->where('t.taxableSupplyDate BETWEEN :startDate AND :endDate')
            ->andWhere('c.vatPayer = 1');
    }
}
