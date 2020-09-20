<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\Row;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Row|null find($id, $lockMode = null, $lockVersion = null)
 * @method Row|null findOneBy(array $criteria, array $orderBy = null)
 * @method Row[]    findAll()
 * @method Row[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Row::class);
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
