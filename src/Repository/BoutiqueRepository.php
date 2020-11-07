<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Boutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boutique[]    findAll()
 * @method Boutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutique::class);
    }

    /**
     * @param $value
     * @return Boutique[]
     */
    public function findByNom( $value )
    {
        $queryBuilder = $this->createQueryBuilder('b');

        return $queryBuilder->where( $queryBuilder->expr()->like( 'b.nom', ':val' ) )
            ->setParameter( 'val', '%'.$value.'%' )
            ->orderBy( 'b.id', 'ASC' )
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $min
     * @param $max
     * @return Boutique[]
     */
    public function findByOpinion( $min, $max ) {
        $queryBuilder = $this->createQueryBuilder('b');

        return $queryBuilder->where( 'b.avis >= :min' )
            ->andWhere( 'b.avis <= :max' )
            ->setParameter( 'min', $min )
            ->setParameter( 'max', $max )
            ->orderBy( 'b.avis', 'ASC' )
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Boutique[] Returns an array of Boutique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boutique
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
