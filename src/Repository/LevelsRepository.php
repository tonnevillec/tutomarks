<?php

namespace App\Repository;

use App\Entity\Levels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Levels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Levels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Levels[]    findAll()
 * @method Levels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LevelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Levels::class);
    }

    // /**
    //  * @return Levels[] Returns an array of Levels objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Levels
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
