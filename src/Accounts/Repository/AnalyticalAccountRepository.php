<?php

namespace App\Accounts\Repository;

use App\Accounts\Entity\Account;
use App\Accounts\Entity\AnalyticalAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnalyticalAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyticalAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyticalAccount[]    findAll()
 * @method AnalyticalAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticalAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyticalAccount::class);
    }

    public function findSimilarByNameOrNumeral(int $accountId, string $nameOrNumeral): array
    {
        return $this->createQueryBuilder('aa')
            ->leftJoin('aa.account', 'a')
            ->andWhere('a.id = :account')
            ->andWhere('(aa.name LIKE :name OR aa.numeral LIKE :name)')
            ->setParameter('account', $accountId)
            ->setParameter('name', '%'.$nameOrNumeral.'%')
            ->orderBy('aa.numeral', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Analytical[] Returns an array of Analytical objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Analytical
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
