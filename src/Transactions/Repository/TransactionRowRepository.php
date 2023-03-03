<?php

namespace App\Transactions\Repository;

use App\Transactions\Entity\TransactionRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionRow[]    findAll()
 * @method TransactionRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionRow::class);
    }

    public function compileAccountStatement(string $account, string $year)
    {
        $startDate = new \DateTime();
        $startDate->setDate($year, 1, 1);
        $startDate->setTime(0, 0, 0);

        $endDate = new \DateTime();
        $endDate->setDate($year, 12, 31);
        $endDate->setTime(23, 59, 59);

        return $this->createQueryBuilder('tr')
            ->leftJoin('tr.transaction', 't')
            ->leftJoin('tr.debtorsAccount', 'md')
            ->leftJoin('tr.creditorsAccount', 'd')
            ->andWhere('md.numeral = :numeral OR d.numeral = :numeral')
            ->andWhere('t.taxableSupplyDate BETWEEN :startDate AND :endDate')
            ->setParameter('numeral', $account)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('t.taxableSupplyDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Row[] Returns an array of Row objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Row
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
