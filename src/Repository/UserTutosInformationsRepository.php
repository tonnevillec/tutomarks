<?php

namespace App\Repository;

use App\Entity\UserTutosInformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTutosInformations|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTutosInformations|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTutosInformations[]    findAll()
 * @method UserTutosInformations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTutosInformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTutosInformations::class);
    }

    // /**
    //  * @return UserTutosInformations[] Returns an array of UserTutosInformations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTutosInformations
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
