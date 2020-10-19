<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Tutos;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * @param Tutos $tutos
     * @return Comments[]
     */
    public function findValid(Tutos $tutos)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tutos = :val')
            ->andWhere('c.is_validate = true')
            ->setParameter('val', $tutos->getId())
            ->orderBy('c.commented_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countForUser(int $user_id): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.user = :user')
            ->setParameter('user', $user_id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
