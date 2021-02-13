<?php

namespace App\Repository\Statement\Vat;

use App\Entity\Statement\Vat\Inspectional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inspectional|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inspectional|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inspectional[]    findAll()
 * @method Inspectional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InspectionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inspectional::class);
    }

    // /**
    //  * @return Inspectional[] Returns an array of Inspectional objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inspectional
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
