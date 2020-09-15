<?php

namespace App\Repository\Statement;

use App\Entity\Statement\TrialBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrialBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrialBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrialBalance[]    findAll()
 * @method TrialBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrialBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrialBalance::class);
    }

    // /**
    //  * @return TrialBalance[] Returns an array of TrialBalance objects
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
    public function findOneBySomeField($value): ?TrialBalance
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
