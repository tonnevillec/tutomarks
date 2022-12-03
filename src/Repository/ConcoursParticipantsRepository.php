<?php

namespace App\Repository;

use App\Entity\ConcoursParticipants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConcoursParticipants>
 *
 * @method ConcoursParticipants|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConcoursParticipants|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConcoursParticipants[]    findAll()
 * @method ConcoursParticipants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcoursParticipantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConcoursParticipants::class);
    }

    public function add(ConcoursParticipants $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConcoursParticipants $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConcoursParticipants[] Returns an array of ConcoursParticipants objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConcoursParticipants
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
