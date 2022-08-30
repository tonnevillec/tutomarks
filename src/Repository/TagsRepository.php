<?php

namespace App\Repository;

use App\Entity\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tags|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tags|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tags[]    findAll()
 * @method Tags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tags::class);
    }

    public function findNotNull(string $orderby = 'title', string $direction = 'asc')
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->leftJoin('t.links', 'l')
            ->andWhere('l.is_publish = 1')
            ->groupBy('t.id')
            ->having('COUNT(l) > 0')
            ->orderBy('title' === $orderby ? 't.title' : 'nb', strtoupper($direction))
            ->getQuery()
            ->getResult()
        ;
    }
}
