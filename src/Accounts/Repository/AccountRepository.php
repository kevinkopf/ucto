<?php

namespace App\Accounts\Repository;

use App\Accounts\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findSimilarByNameOrNumeral(string $search): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name LIKE :search')
            ->orWhere('a.numeral LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('a.numeral', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    private function getClosingAccountsIds()
    {
        $subRsm = new ResultSetMapping();

        $subRsm->addScalarResult('id', 'id');

        $prefetchSubquery = $this->getEntityManager()
            ->createNativeQuery("SELECT a.id FROM accounts a LEFT JOIN accounts_types t ON a.type_id = t.id WHERE t.name = ?", $subRsm);
        $prefetchSubquery->setParameter(1, Account\Type::TYPE_STATEMENT);

        return $prefetchSubquery->getArrayResult();
    }

    public function compileTrialBalance(int $year, int $month, int $day)
    {
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
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.creditors_account_id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.creditors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") + ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.debtors_account_id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.debtors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") ) AS openingAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.creditors_account_id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.creditors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS debtorAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.debtors_account_id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.debtors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS creditorAmount " .
            "FROM accounts a " .
            "LEFT JOIN accounts_types t " .
                "ON a.type_id = t.id " .
            "WHERE t.name != :statementType " .
            "HAVING openingAmount > 0 " .
                "OR debtorAmount > 0 " .
                "OR creditorAmount > 0 " .
            "";

        return $this->getEntityManager()
            ->createNativeQuery($query, $rsm)
            ->setParameter('sub', $this->getClosingAccountsIds())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('statementType', Account\Type::TYPE_STATEMENT)
            ->setParameter('statementSubType', ['702', '710'])
            ->getScalarResult();
    }

    public function compileTrialBalanceForAnalytical(int $year, int $month, int $day, Account $account)
    {
        $startDate = new \DateTime();
        $startDate->setDate($year, 1, 1);
        $startDate->setTime(0, 0, 0);

        $endDate = new \DateTime();
        $endDate->setDate($year, $month, $day);
        $endDate->setTime(23, 59, 59);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('analyticalId', 'analyticalId');
        $rsm->addScalarResult('openingAmount', 'openingAmount');
        $rsm->addScalarResult('debtorAmount', 'debtorAmount');
        $rsm->addScalarResult('creditorAmount', 'creditorAmount');

        $query = "SELECT " .
            "aa.id AS analyticalId, " .
            "a.id AS id, " .
            "( ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.creditors_account_id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND aa.id = tr.debtors_account_analytical_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.creditors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") + ( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.debtors_account_id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND aa.id = tr.creditors_account_analytical_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.debtors_account_id IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") ) AS openingAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.creditors_account_id " .
                "WHERE a.id = tr.debtors_account_id " .
                    "AND aa.id = tr.debtors_account_analytical_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.creditors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS debtorAmount, " .
            "( SELECT COALESCE(SUM(tr.amount), 0) " .
                "FROM transactions_rows tr " .
                "LEFT JOIN transactions td " .
                    "ON tr.transaction_id = td.id " .
                "LEFT JOIN accounts ctra " .
                    "ON ctra.id = tr.debtors_account_id " .
                "WHERE a.id = tr.creditors_account_id " .
                    "AND aa.id = tr.creditors_account_analytical_id " .
                    "AND ctra.numeral NOT IN(:statementSubType) " .
                    "AND tr.debtors_account_id NOT IN (:sub) " .
                    "AND td.taxable_supply_date >= :startDate " .
                    "AND td.taxable_supply_date <= :endDate " .
            ") AS creditorAmount " .
            "FROM accounts_analytical aa " .
            "LEFT JOIN accounts a " .
                "ON a.id = aa.account_id " .
            "LEFT JOIN accounts_types t " .
                "ON a.type_id = t.id " .
            "WHERE a.id = :parentAccount " .
            "AND t.name != :statementType " .
            "HAVING openingAmount > 0 " .
                "OR debtorAmount > 0 " .
                "OR creditorAmount > 0 " .
            "";

        return $this->getEntityManager()
            ->createNativeQuery($query, $rsm)
            ->setParameter('parentAccount', $account->getId())
            ->setParameter('sub', $this->getClosingAccountsIds())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('statementType', Account\Type::TYPE_STATEMENT)
            ->setParameter('statementSubType', ['702', '710'])
            ->getScalarResult();
    }
}
