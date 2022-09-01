<?php

namespace App\Entity;

use App\Repository\HebdooRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HebdooRepository::class)]
class Hebdoo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['show_hebdoos'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['show_hebdoos'])]
    private string $title;

    #[ORM\Column(length: 255)]
    #[Assert\Url]
    #[Groups(groups: ['show_hebdoos'])]
    private string $url;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['show_hebdoos'])]
    private string $pseudo;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['show_hebdoos'])]
    private ?\DateTimeInterface $created_at;

    #[ORM\ManyToMany(targetEntity: Tags::class)]
    #[Groups(groups: ['show_hebdoos'])]
    private Collection $tags;

    #[ORM\ManyToOne]
    #[Groups(groups: ['show_hebdoos'])]
    private ?Languages $language = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->tags = new ArrayCollection();
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

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getLanguage(): ?Languages
    {
        return $this->language;
    }

    public function setLanguage(?Languages $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function __toString(): string
    {
        return (string) '['.$this->getId().'] '.$this->getTitle();
    }

    private array $english_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    private array $french_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private array $english_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    private array $french_months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    #[Groups(groups: ['show_hebdoos'])]
    public function getCreatedAtLocal(string $format = 'l j F Y'): string
    {
        return str_replace(
            $this->english_months,
            $this->french_months,
            str_replace(
                $this->english_days,
                $this->french_days,
                $this->created_at->format($format)
            )
        );
    }
}
