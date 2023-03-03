<?php

namespace App\Accounts\Repository;

use App\Accounts\Entity\AccountKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountKind[]    findAll()
 * @method AccountKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountKind::class);
    }

    // /**
    //  * @return Kind[] Returns an array of Kind objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kind
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
