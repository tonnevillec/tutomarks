<?php

namespace App\Repository;

use App\Entity\Channels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Channels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Channels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Channels[]    findAll()
 * @method Channels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Channels::class);
    }

    public function findAllChannels(string $orderby = 'nom', string $direction = 'asc')
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('COUNT(t) nb')
            ->leftJoin('c.tutos', 't')
            ->andWhere('t.available = 1')
            ->groupBy('c.id')
        ;

        if($orderby === 'nom') {
            $qb->orderBy('c.title', strtoupper($direction));
        } else {
            $qb->orderBy('nb', strtoupper($direction));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllbyTutosNumber(int $nb = 6)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('COUNT(t) nb')
            ->leftJoin('c.tutos', 't')
            ->groupBy('c.id')
            ->orderBy('nb', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }
}
