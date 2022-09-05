<?php

namespace App\Repository;

use App\Entity\Hebdoo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hebdoo>
 *
 * @method Hebdoo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hebdoo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hebdoo[]    findAll()
 * @method Hebdoo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HebdooRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hebdoo::class);
    }

    public function add(Hebdoo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hebdoo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderByDate(): array
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.created_at', 'DeSC')
            ->getQuery()
            ->getResult()
        ;
    }
}
