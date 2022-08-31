<?php

namespace App\Repository;

use App\Entity\HebdooSemaine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HebdooSemaine>
 *
 * @method HebdooSemaine|null find($id, $lockMode = null, $lockVersion = null)
 * @method HebdooSemaine|null findOneBy(array $criteria, array $orderBy = null)
 * @method HebdooSemaine[]    findAll()
 * @method HebdooSemaine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HebdooSemaineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HebdooSemaine::class);
    }

    public function add(HebdooSemaine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HebdooSemaine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HebdooSemaine[] Returns an array of HebdooSemaine objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HebdooSemaine
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
