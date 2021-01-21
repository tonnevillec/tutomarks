<?php

namespace App\Entity;

use App\Repository\UserTutosInformationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTutosInformationsRepository::class)
 */
class UserTutosInformations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tutosInformations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tutos::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $shown;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pined;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $postit;

    public function __construct()
    {
        $this->shown = false;
        $this->pined = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTutos(): ?Tutos
    {
        return $this->tutos;
    }

    public function setTutos(?Tutos $tutos): self
    {
        $this->tutos = $tutos;

        return $this;
    }

    public function getShown(): ?bool
    {
        return $this->shown;
    }

    public function setShown(bool $shown): self
    {
        $this->shown = $shown;

        return $this;
    }

    public function getPined(): ?bool
    {
        return $this->pined;
    }

    public function setPined(bool $pined): self
    {
        $this->pined = $pined;

        return $this;
    }

    public function getPostit(): ?string
    {
        return $this->postit;
    }

    public function setPostit(?string $postit): self
    {
        $this->postit = $postit;

        return $this;
    }
}
