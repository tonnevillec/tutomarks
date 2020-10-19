<?php

namespace App\Entity;

use App\Repository\BadgesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BadgesRepository::class)
 */
class Badges
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $action_count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BadgesUnlock", mappedBy="badge")
     */
    private $unlocks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActionName(): ?string
    {
        return $this->action_name;
    }

    public function setActionName(string $action_name): self
    {
        $this->action_name = $action_name;

        return $this;
    }

    public function getActionCount(): ?int
    {
        return $this->action_count;
    }

    public function setActionCount(int $action_count): self
    {
        $this->action_count = $action_count;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Badges[]
     */
    public function getUnlocks(): Collection
    {
        return $this->unlocks;
    }

    /**
     * @param array $unlocks
     * @return Badges
     */
    public function setUnlocks(array $unlocks): Badges
    {
        $this->unlocks = $unlocks;
        return $this;
    }
}
