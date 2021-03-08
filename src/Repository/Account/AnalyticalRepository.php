<?php

namespace App\Repository\Account;

use App\Entity\Account;
use App\Entity\Account\Analytical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Analytical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Analytical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Analytical[]    findAll()
 * @method Analytical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analytical::class);
    }

    public function findSimilarByNameOrNumeral(Account $account, string $nameOrNumeral): array
    {
        return $this->createQueryBuilder('aa')
            ->leftJoin('aa.account', 'a')
            ->andWhere('a = :account')
            ->andWhere('(aa.name LIKE :name OR aa.numeral LIKE :name)')
            ->setParameter('account', $account)
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
