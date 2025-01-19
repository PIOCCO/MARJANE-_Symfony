<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for the Customer entity.
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Find customers by a partial match of their address.
     *
     * @param string $address
     * @return Customer[]
     */
    public function findByAddress(string $address): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.address LIKE :address')
            ->setParameter('address', '%' . $address . '%')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find customers by email.
     *
     * @param string $email
     * @return Customer|null
     */
    public function findOneByEmail(string $email): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
