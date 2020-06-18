<?php
namespace App\Service;

use App\Entity\Evaluations;
use App\Entity\Tutos;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EvaluationsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Tutos $tuto
     * @return float|null
     */
    public function getTutosMoy(Tutos $tuto)
    {
        $all = $tuto->getEvaluations();

        $count = count($all);
        if($count !== 0){
            $somme = 0;
            foreach ($all as $eval) {
                $somme += $eval->getNotation();
            }

            return $somme / $count;
        }

        return null;
    }

    /**
     * @param Tutos $tuto
     * @param User $user
     * @return float|null
     */
    public function getForUser(Tutos $tuto, User $user)
    {
        $eval = $this->em->getRepository(Evaluations::class)->findOneBy(['user' => $user, 'tutos' => $tuto]);
        if(!$eval){
            return null;
        }

        return $eval->getNotation();
    }
}