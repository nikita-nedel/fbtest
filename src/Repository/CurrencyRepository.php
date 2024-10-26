<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

        /**
         * @return Currency[] Returns an array of Currency objects
         */
        public function findAllActive(): array
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.isActive = 1')
                ->orderBy('c.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

    /**
     * @param int $value
     * @return Currency|null
     */
    public function findOneById(int $value): ?Currency
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
}
