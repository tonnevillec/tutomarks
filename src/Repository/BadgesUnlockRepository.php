<?php

namespace App\Repository;

use App\Entity\BadgesUnlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BadgesUnlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method BadgesUnlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method BadgesUnlock[]    findAll()
 * @method BadgesUnlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgesUnlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BadgesUnlock::class);
    }

    // /**
    //  * @return BadgesUnlock[] Returns an array of BadgesUnlock objects
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
    public function findOneBySomeField($value): ?BadgesUnlock
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
