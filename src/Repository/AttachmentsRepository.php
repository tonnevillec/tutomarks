<?php

namespace App\Repository;

use App\Entity\Attachments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attachments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attachments[]    findAll()
 * @method Attachments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attachments::class);
    }

    // /**
    //  * @return Attachments[] Returns an array of Attachments objects
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
    public function findOneBySomeField($value): ?Attachments
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
