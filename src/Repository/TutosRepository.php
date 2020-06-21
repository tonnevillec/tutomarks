<?php

namespace App\Repository;

use App\Entity\Tutos;
use App\Entity\TutoSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tutos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutos[]    findAll()
 * @method Tutos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutos::class);
    }

    public function findLatest(int $nb)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.published_at', 'desc')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param TutoSearch $search
     * @return Query
     */
    public function findAllVisible(TutoSearch $search) :Query
    {
        $query = $this->findVisible();

        if($search->getSearch()) {
            $query = $query
                ->andWhere('t.title like :search OR t.description like :search')
                ->setParameter(':search', '%'.$search->getSearch().'%')
            ;
        }

        if($search->getCategory()) {
            $query  = $query
                ->andWhere('t.category = :category')
                ->setParameter(':category', $search->getCategory())
            ;
        }

        if($search->getCreator()) {
            $query  = $query
                ->andWhere('t.creator like :creator')
                ->setParameter(':creator', '%'.$search->getCreator().'%')
            ;
        }

        if($search->getEvaluation()) {
            $query  = $query
                ->andWhere('t.moy >= :eval')
                ->setParameter(':eval', $search->getEvaluation())
            ;
        }

        if($search->getLangue()) {
            $query  = $query
                ->andWhere('t.langue = :langue')
                ->setParameter(':langue', $search->getLangue())
            ;
        }

        if($search->getTags() && count($search->getTags()) !== 0) {
            $tags = [];
            foreach ($search->getTags() as $tag){
                $tags[] = $tag->getId();
            }
            $query = $query
                ->leftJoin('t.tags', 'tags')
                ->andWhere('tags.id IN (:tags)')
                ->setParameter(':tags', $tags)
            ;
        }

        $query = $query->orderBy('t.published_at', 'desc');

        return $query->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public function findVisible(): QueryBuilder
    {
        return $this->createQueryBuilder('t');
    }

}