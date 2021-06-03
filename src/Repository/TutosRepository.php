<?php

namespace App\Repository;

use App\Entity\Tutos;
use App\Entity\TutoSearch;
use App\Entity\User;
use App\Entity\UserTutosInformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Tutos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutos[]    findAll()
 * @method Tutos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutosRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Tutos::class);
        $this->security = $security;
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

    public function findLatestCategory(string $category = 'video', int $nb = 4)
    {
        return $this->createQueryBuilder('t')
            ->join('t.category', 'c')
            ->andWhere('c.homekey = :value')
            ->setParameter('value', $category)
            ->orderBy('t.published_at', 'desc')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLatestForMe(User $user, int $nb = 6)
    {
        return $this->forMe($user)
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findForMe(User $user, TutoSearch $search)
    {
        $query = $this->findVisible();

        $query = $query->andWhere('t.published_by = :user')
            ->setParameter('user', $user);

        $query = $this->search($query, $search);

        $query = $query->orderBy('t.published_at', 'desc');

        return $query->getQuery();
    }

    public function forMe(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.published_by = :user')
            ->setParameter('user', $user)
            ->orderBy('t.published_at', 'desc')
            ;
    }

    /**
     * @param TutoSearch $search
     * @return Query
     */
    public function findAllVisible(TutoSearch $search) :Query
    {
        $query = $this->findVisible();

        $query = $this->search($query, $search);

        $query = $query->orderBy('t.published_at', 'desc');

        return $query->getQuery();
    }

    public function search($query, $search)
    {
        if($search->getSearch()) {
            $query = $query
                // ->andWhere('t.title like :search OR t.description like :search')
                ->andWhere('MATCH_AGAINST(t.title, t.description) AGAINST (:search boolean)>0')
                ->setParameter(':search', $search->getSearch())
            ;
        }

        if($search->getCategory()) {
            $query  = $query
                ->andWhere('t.category = :category')
                ->setParameter(':category', $search->getCategory())
            ;
        }

        if($search->getChannel()) {
            $query  = $query
                ->andWhere('t.channel = :channel')
                ->setParameter(':channel', $search->getChannel())
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

        if($search->getPrice()) {
            $query  = $query
                ->andWhere('t.price = :price')
                ->setParameter(':price', $search->getPrice())
            ;
        }

        if($search->getMinlevel()) {
            $query  = $query
                ->leftJoin('t.level', 'level')
                ->andWhere('level.rank >= :level')
                ->setParameter(':level', $search->getMinlevel())
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

        if(!is_null($search->getPined())) {
            $infos = $this->_em
                ->getRepository(UserTutosInformations::class)
                ->findBy([
                    'user'  => $this->security->getUser()->getId(),
                    'pined' => $search->getPined()
                ]);
            $pined = [];
            foreach($infos as $userTuto) {
                $pined[] = $userTuto->getTutos()->getId();
            }
            $query = $query
                ->andWhere('t.id IN (:pined)')
                ->setParameter(':pined', $pined)
            ;
        }

        if(!is_null($search->getShown())) {
            $infos = $this->_em
                ->getRepository(UserTutosInformations::class)
                ->findBy([
                    'user'  => $this->security->getUser()->getId(),
                    'shown' => $search->getShown()
                ]);
            $shown = [];
            foreach($infos as $userTuto) {
                $shown[] = $userTuto->getTutos()->getId();
            }
            $query = $query
                ->andWhere('t.id IN (:shown)')
                ->setParameter(':shown', $shown)
            ;
        }

        return $query;
    }

    /**
     * @return QueryBuilder
     */
    public function findVisible(): QueryBuilder
    {
        return $this->createQueryBuilder('t');
    }

    /**
     * @param int $user_id
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countForUser(int $user_id): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.published_by = :user')
            ->setParameter('user', $user_id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
