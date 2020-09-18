<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

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
            ->getResult();
    }

    public function compileTrialBalance(int $year, int $month, int $day)
    {
        $subRsm = new ResultSetMapping();

        $subRsm->addScalarResult('id', 'id');

        $prefetchSubquery = $this->getEntityManager()
            ->createNativeQuery("SELECT a.id FROM accounts a LEFT JOIN accounts_types t ON a.type_id = t.id WHERE t.name = ?", $subRsm);
        $prefetchSubquery->setParameter(1, Account\Type::TYPE_STATEMENT);
        $subqueryResult = $prefetchSubquery->getArrayResult();

        $startDate = new \DateTime();
        $startDate->setDate($year, 1, 1);
        $startDate->setTime(0, 0, 0);

        $endDate = new \DateTime();
        $endDate->setDate($year, $month, $day);
        $endDate->setTime(23, 59, 59);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('openingAmount', 'openingAmount');
        $rsm->addScalarResult('debtorAmount', 'debtorAmount');
        $rsm->addScalarResult('creditorAmount', 'creditorAmount');

        $query = "SELECT " .
            "a.id AS id, " .
            "( ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND tr.creditors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") + ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND tr.debtors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") ) AS openingAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND tr.creditors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS debtorAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND tr.debtors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS creditorAmount " .
            "FROM accounts a " .
            "LEFT JOIN accounts_types t " .
                "ON a.type_id = t.id " .
            "WHERE t.name != :type " .
            "HAVING openingAmount > 0 " .
                "OR debtorAmount > 0 " .
                "OR creditorAmount > 0 " .
            "";

        return $this->getEntityManager()
            ->createNativeQuery($query, $rsm)
            ->setParameter('sub', $subqueryResult)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('type', Account\Type::TYPE_STATEMENT)
            ->getScalarResult();
    }
}
