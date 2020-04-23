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
            ->getResult()
            ;
    }

    public function findAllThisYearGroupedByAccount(int $year)
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
        $endDate->setDate($year, 12, 31);
        $endDate->setTime(23, 59, 59);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('numeral', 'numeral');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('totalBeginAmount', 'totalBeginAmount');
        $rsm->addScalarResult('totalDebtorAmount', 'totalDebtorAmount');
        $rsm->addScalarResult('totalCreditorAmount', 'totalCreditorAmount');

        $query = "SELECT " .
            "a.numeral AS numeral, " .
            "a.name AS name, " .
            "t.name AS type, " .
            "( ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND tr.creditors_account_id IN (:sub) " .
            ") + ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND tr.debtors_account_id IN (:sub) " .
            ") ) AS totalBeginAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND tr.creditors_account_id NOT IN (:sub) " .
            ") AS totalDebtorAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND tr.debtors_account_id NOT IN (:sub) " .
            ") AS totalCreditorAmount " .
            "FROM accounts a " .
            "LEFT JOIN accounts_types t " .
                "ON a.type_id = t.id " .
            "WHERE t.name != :type " .
            "HAVING totalBeginAmount > 0 " .
                "OR totalDebtorAmount > 0 " .
                "OR totalCreditorAmount > 0 " .
            "";

        return $this->getEntityManager()
            ->createNativeQuery($query, $rsm)
            ->setParameter('sub', $subqueryResult)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('type', Account\Type::TYPE_STATEMENT)
            ->getScalarResult()
            ;

    }
}
