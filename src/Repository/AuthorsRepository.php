<?php

namespace App\Repository;

use App\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Authors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Authors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Authors[]    findAll()
 * @method Authors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authors::class);
    }

    public function findTop($nb = 10)
    {
        return $this->findWithCount()
            ->orderBy('nb', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllAuthors(string $orderby = 'title', string $direction = 'asc')
    {
        return $this->findWithCount()
            ->orderBy('title' === $orderby ? 'a.title' : 'nb', strtoupper($direction))
            ->getQuery()
            ->getResult()
        ;
    }

    private function findWithCount()
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->addSelect('COUNT(l) nb')
            ->leftJoin('a.links', 'l')
            ->andWhere('l.is_publish = 1')
            ->groupBy('a.id')
        ;
    }
}
