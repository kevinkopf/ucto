<?php

namespace App\Repository\Account;

use App\Entity\Account\LiabilitiesType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LiabilitiesType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiabilitiesType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiabilitiesType[]    findAll()
 * @method LiabilitiesType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiabilitiesTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiabilitiesType::class);
    }

    // /**
    //  * @return LiabilitiesType[] Returns an array of LiabilitiesType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiabilitiesType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
