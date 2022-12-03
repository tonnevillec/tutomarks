<?php

namespace App\Repository;

use App\Entity\ConfEventPlaylist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfEventPlaylist>
 *
 * @method ConfEventPlaylist|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfEventPlaylist|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfEventPlaylist[]    findAll()
 * @method ConfEventPlaylist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfEventPlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfEventPlaylist::class);
    }

    public function add(ConfEventPlaylist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConfEventPlaylist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConfEventPlaylist[] Returns an array of ConfEventPlaylist objects
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

//    public function findOneBySomeField($value): ?ConfEventPlaylist
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
