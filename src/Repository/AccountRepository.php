<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    /**
     * AccountRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function findBySimilarByNameOrNumeral(string $nameOrNumeral)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name LIKE :name')
            ->orWhere('a.numeral LIKE :name')
            ->setParameter('name', '%'.$nameOrNumeral.'%')
            ->orderBy('a.numeral', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
