<?php

namespace App\Repository;

use App\Entity\Users;
use App\Entity\YoutubeLinks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method YoutubeLinks|null find($id, $lockMode = null, $lockVersion = null)
 * @method YoutubeLinks|null findOneBy(array $criteria, array $orderBy = null)
 * @method YoutubeLinks[]    findAll()
 * @method YoutubeLinks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YoutubeLinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YoutubeLinks::class);
    }

    /**
     * @return YoutubeLinks[] Returns an array of YoutubeLinks objects
     */
    public function findLatestPublished($nb = 8): array
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.is_publish = 1')
            ->orderBy('y.published_at', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForMe(Users $users)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.published_by = :user')
            ->setParameter('user', $users->getId())
            ->orderBy('y.published_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWeeklyPublished()
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.is_publish = 1')
            ->andWhere('y.published_at >= :date')
            ->setParameter('date', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('y.published_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
