<?php

namespace App\Repository\Statement;

use App\Entity\Statement\TrialBalanceRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrialBalanceRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrialBalanceRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrialBalanceRecord[]    findAll()
 * @method TrialBalanceRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrialBalanceRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrialBalanceRecord::class);
    }

    // /**
    //  * @return TrialBalanceRecord[] Returns an array of TrialBalanceRecord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrialBalanceRecord
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
