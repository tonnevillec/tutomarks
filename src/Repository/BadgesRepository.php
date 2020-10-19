<?php

namespace App\Repository;

use App\Entity\Badges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badges|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badges|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badges[]    findAll()
 * @method Badges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badges::class);
    }

    /**
     * @param int $user_id
     * @param string $action
     * @param int $action_count
     * @return Badges
     */
    public function findWithUnlockForAction(int $user_id, string $action, int $action_count): Badges
    {
        return $this->createQueryBuilder('b')
            ->where('b.action_name = :action')
            ->andWhere('b.action_count = :action_count')
            ->andWhere('u.user = :user_id OR u.user IS NULL')
            ->leftJoin('b.unlocks', 'u')
            ->select('b, u')
            ->setParameters([
                'action'        => $action,
                'action_count'  => $action_count,
                'user_id'       => $user_id
            ])
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
