<?php

namespace App\Repository;

use App\Entity\Evaluations;
use App\Entity\Tutos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evaluations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluations[]    findAll()
 * @method Evaluations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluations::class);
    }

    /**
     * @param Tutos $id
     * @return float|int
     */
    public function findMoyenne(Tutos $id)
    {
        $all = $this->createQueryBuilder('e')
            ->andWhere('e.tuto = :tuto')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;

        $count = $all->count();
        $somme = 0;
        foreach ($all as $eval) {
            $somme += $eval->notation;
        }

        return $somme / $count;
    }

    // /**
    //  * @return Evaluations[] Returns an array of Evaluations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evaluations
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
