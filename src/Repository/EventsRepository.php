<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    public function findEventsByDate(int $nb, string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.started_at >= :date')
            ->setParameter('date', (new \DateTime())->modify('-2 day'))
            ->orderBy('e.started_at', $order)
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFuturEvents(string $order = 'ASC')
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.started_at >= :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('e.started_at', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFinishEvents(string $order = 'ASC')
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.started_at < :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('e.started_at', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWeeklyPublished()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.published_at >= :date')
            ->setParameter('date', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('e.published_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
