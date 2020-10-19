<?php

namespace App\Entity;

use App\Repository\BadgesUnlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BadgesUnlockRepository::class)
 */
class BadgesUnlock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Badges
     * @ORM\ManyToOne(targetEntity="App\Entity\Badges", inversedBy="unlocks")
     */
    private $badge;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Badges
     */
    public function getBadge(): Badges
    {
        return $this->badge;
    }

    /**
     * @param Badges $badge
     * @return BadgesUnlock
     */
    public function setBadge(Badges $badge): BadgesUnlock
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return BadgesUnlock
     */
    public function setUser(User $user): BadgesUnlock
    {
        $this->user = $user;
        return $this;
    }


}
