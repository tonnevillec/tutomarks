<?php

namespace App\Managers;

use App\Entity\Badges;
use App\Entity\BadgesUnlock;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectManager;

class BadgesManager {

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;    
    }

    /**
     * Check if a badge exist for this action and action occurence and unlock it for the user
     * @param User $user
     * @param $action
     * @param int $action_count
     */
    public function checkAndUnlock(User $user, $action, int $action_count): void
    {
        try {
            $badge = $this->manager
                ->getRepository(Badges::class)
                ->findWithUnlockForAction($user->getId(), $action, $action_count);

            if ($badge->getUnlocks()->isEmpty()) {
                $unlock = (new BadgesUnlock())
                    ->setBadge($badge)
                    ->setUser($user);
                $this->manager->persist($unlock);
                $this->manager->flush();
            }
        } catch (NoResultException $e) {

        }
    }
}