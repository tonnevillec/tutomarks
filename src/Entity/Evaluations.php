<?php

namespace App\Entity;

use App\Repository\EvaluationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationsRepository::class)
 */
class Evaluations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tutos::class, inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutos;

    /**
     * @ORM\Column(type="float")
     */
    private $notation;

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

    public function getNotation(): ?float
    {
        return $this->notation;
    }

    public function setNotation(float $notation): self
    {
        $this->notation = $notation;

        return $this;
    }

}
