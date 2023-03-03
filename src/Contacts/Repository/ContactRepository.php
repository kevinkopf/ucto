<?php

namespace App\Contacts\Repository;

use App\Contacts\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findByCriteria(ContactSearchCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit)
        ;

        if ($criteria->name) {
            $qb->orWhere('c.name LIKE :name')
                ->setParameter('name', '%'.$criteria->name.'%');
        }

        if ($criteria->address) {
            $qb->orWhere('c.address LIKE :address')
                ->setParameter('address', '%'.$criteria->address.'%');
        }

        if ($criteria->registrationNumber) {
            $qb->orWhere('c.registrationNumber LIKE :registrationNumber')
                ->setParameter('registrationNumber', '%'.$criteria->registrationNumber.'%');
        }

        if ($criteria->vatNumber) {
            $qb->orWhere('c.vatNumber LIKE :vatNumber')
                ->setParameter('vatNumber', '%'.$criteria->vatNumber.'%');
        }

        return $qb->getQuery()->getResult();
    }
}
