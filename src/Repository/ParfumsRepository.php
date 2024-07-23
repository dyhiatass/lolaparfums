<?php

namespace App\Repository;

use App\Entity\Parfums;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parfums>
 */
class ParfumsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parfums::class);
    }

    //    /**
    //     * @return Parfums[] Returns an array of Parfums objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Parfums
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByParfums( $marque, $concentration, $prix, $genre)
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.detailsParfums', 'd') 
        ->addSelect('d')
        ->andWhere('p.isCoffret = :isCoffret')
            ->setParameter('isCoffret', false); 

        if ($marque) {
            $qb->andWhere('p.marque = :marque ')
                ->setParameter('marque', $marque);
        }

        if ($concentration) {
            $qb->andWhere('p.concentration = :concentration')
                ->setParameter('concentration', $concentration);
        }

        if ($prix) {
            [$minPrix, $maxPrix] = explode('-', $prix);
            if ($minPrix !== null && $maxPrix !== null) {
                $qb->andWhere('d.prix >= :minPrix')
                    ->setParameter('minPrix', $minPrix);
                $qb->andWhere('d.prix <= :maxPrix')
                    ->setParameter('maxPrix', $maxPrix);
            }
        }

        if ($genre) {
            $qb->andWhere('p.genre = :genre')
                ->setParameter('genre', $genre);
        }

        return $qb->getQuery()->getResult();
    }
    public function findByCoffrets( $marque, $prix)
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.detailsParfums', 'd') 
        ->addSelect('d')
        ->andWhere('p.isCoffret = :isCoffret')
            ->setParameter('isCoffret', true); 
        if ($marque) {
            $qb->andWhere('p.marque = :marque ')
                ->setParameter('marque', $marque);
        } 
        if ($prix) {
            [$minPrix, $maxPrix] = explode('-', $prix);
            if ($minPrix !== null && $maxPrix !== null) {
                $qb->andWhere('d.prix >= :minPrix')
                    ->setParameter('minPrix', $minPrix);
                $qb->andWhere('d.prix <= :maxPrix')
                    ->setParameter('maxPrix', $maxPrix);   } }
        return $qb->getQuery()->getResult();
    }


    
        public function findByBestsellers( $marque, $prix)
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.detailsParfums', 'd') 
        ->addSelect('d')
        ->andWhere('p.meilleursVente = :meilleursVente')
            ->setParameter('meilleursVente', true); 
        if ($marque) {
            $qb->andWhere('p.marque = :marque ')
                ->setParameter('marque', $marque);
        }
        
        if ($prix) {
            [$minPrix, $maxPrix] = explode('-', $prix);
            if ($minPrix !== null && $maxPrix !== null) {
                $qb->andWhere('d.prix >= :minPrix')
                    ->setParameter('minPrix', $minPrix);
                $qb->andWhere('d.prix <= :maxPrix')
                    ->setParameter('maxPrix', $maxPrix);
            }
        }

        

        return $qb->getQuery()->getResult();
    }
}
