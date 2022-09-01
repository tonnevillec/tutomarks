<?php

namespace App\Entity;

use App\Repository\HebdooSemaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HebdooSemaineRepository::class)]
class HebdooSemaine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column]
    private bool $is_publish = false;

    #[ORM\Column(length: 255)]
    private string $youtube;

    #[ORM\ManyToMany(targetEntity: Hebdoo::class)]
    private Collection $ressources;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isIsPublish(): bool
    {
        return $this->is_publish;
    }

    public function setIsPublish(bool $is_publish): self
    {
        $this->is_publish = $is_publish;

        return $this;
    }

    public function getYoutube(): string
    {
        return $this->youtube;
    }

    public function setYoutube(string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * @return Collection<int, Hebdoo>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Hebdoo $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
        }

        return $this;
    }

    public function removeRessource(Hebdoo $ressource): self
    {
        $this->ressources->removeElement($ressource);

        return $this;
    }
}
