<?php

namespace App\Repository;

use App\Entity\DetailsParfum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsParfum>
 */
class DetailsParfumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsParfum::class);
    }

    //    /**
    //     * @return DetailsParfum[] Returns an array of DetailsParfum objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DetailsParfum
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function promotionCoffret(){
        return $this->createQueryBuilder('dp')
            ->join('dp.parfums', 'p')
            ->where('dp.promotion = :promotion')
            ->andWhere('p.isCoffret = :isCoffret')
            ->setParameter('promotion', true)
            ->setParameter('isCoffret', true)
            ->getQuery()
            ->getResult();
    }
    
}
