<?php

namespace App\Repository;

use App\Entity\Links;
use App\Entity\LinkSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Links|null find($id, $lockMode = null, $lockVersion = null)
 * @method Links|null findOneBy(array $criteria, array $orderBy = null)
 * @method Links[]    findAll()
 * @method Links[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Links::class);
    }

    public function findLatestPublished($nb = 6)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.is_publish = 1')
            ->orderBy('l.published_at', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLatestSimpleLinks($value, $nb = 6)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.category', 'c')
            ->andWhere('c.code = :val')
            ->setParameter('val', $value)
            ->orderBy('l.published_at', 'DESC')
            ->getQuery()
            ->setMaxResults($nb)
            ->getResult()
        ;
    }

    public function findAllPublished(LinkSearch $search, string $orderby = 'l.title', string $direction = 'desc')
    {
        $query = $this->createQueryBuilder('l')
            ->andWhere('l.is_publish = 1')
        ;

        if($search->getSearch()) {
            $query = $query
                ->andWhere('MATCH_AGAINST(l.title, l.description) AGAINST (:search boolean)>0')
                ->setParameter('search', '*'.$search->getSearch().'*')
            ;
        }

        if($search->getCategories() && count($search->getCategories()) !== 0) {
            $categories = [];
            foreach ($search->getCategories() as $category){
                $categories[] = $category->getId();
            }
            $query = $query
                ->leftJoin('l.category', 'categories')
                ->andWhere('categories.id IN (:categories)')
                ->setParameter(':categories', $categories)
            ;
        }

        if($search->getLanguages() && count($search->getLanguages()) !== 0) {
            $languages = [];
            foreach ($search->getLanguages() as $language){
                $languages[] = $language->getId();
            }
            $query = $query
                ->leftJoin('l.language', 'languages')
                ->andWhere('languages.id IN (:languages)')
                ->setParameter(':languages', $languages)
            ;
        }

        if($search->getTags() && count($search->getTags()) !== 0) {
            $tags = [];
            foreach ($search->getTags() as $tag){
                $tags[] = $tag->getId();
            }
            $query = $query
                ->leftJoin('l.tags', 'tags')
                ->andWhere('tags.id IN (:tags)')
                ->setParameter(':tags', $tags)
            ;
        }

        if($search->getAuthors() && count($search->getAuthors()) !== 0) {
            $authors = [];
            foreach ($search->getAuthors() as $author){
                $authors[] = $author->getId();
            }
            $query = $query
                ->leftJoin('l.author', 'authors')
                ->andWhere('authors.id IN (:authors)')
                ->setParameter(':authors', $authors)
            ;
        }

        if($orderby === 'l.title') {
            $query->orderBy($orderby, strtoupper($direction));
        } else {
            $query->orderBy('l.published_at', strtoupper($direction));
        }

        return $query->getQuery()->getResult();
    }
}
