<?php

namespace App\Repository\Account;

use App\Entity\Account\AssetsAndLiabilitiesType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssetsAndLiabilitiesType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetsAndLiabilitiesType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetsAndLiabilitiesType[]    findAll()
 * @method AssetsAndLiabilitiesType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetsAndLiabilitiesTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetsAndLiabilitiesType::class);
    }

    // /**
    //  * @return AssetsAndLiabilitiesType[] Returns an array of AssetsAndLiabilitiesType objects
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
    public function findOneBySomeField($value): ?AssetsAndLiabilitiesType
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
