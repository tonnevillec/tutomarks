<?php

namespace App\Repository;

use App\Entity\Addurl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Addurl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Addurl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Addurl[]    findAll()
 * @method Addurl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddurlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Addurl::class);
    }

    // /**
    //  * @return Addurl[] Returns an array of Addurl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Addurl
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
