<?php

namespace App\Repository;

use App\Entity\Langues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Langues|null find($id, $lockMode = null, $lockVersion = null)
 * @method Langues|null findOneBy(array $criteria, array $orderBy = null)
 * @method Langues[]    findAll()
 * @method Langues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Langues::class);
    }

    // /**
    //  * @return Langues[] Returns an array of Langues objects
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
    public function findOneBySomeField($value): ?Langues
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
