<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
#[UniqueEntity(fields: ['slug'], message: 'posts.slug_unique', errorPath: 'title')]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(groups: ['posts.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['posts.show'])]
    private ?string $title = null;

    #[ORM\Column(type:'string', length: 255)]
    #[Groups(groups: ['posts.show'])]
    private ?string $slug = null;

    #[ORM\Column(type:'string', length: 255, nullable: true)]
    #[Groups(groups: ['posts.show'])]
    private ?string $summary = null;

    #[ORM\Column(type: 'text')]
    #[Groups(groups: ['posts.show'])]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(groups: ['posts.show'])]
    private \DateTimeInterface $published_at;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['posts.show'])]
    private ?bool $is_published = false;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['posts.show'])]
    private ?Users $author = null;

    #[ORM\ManyToMany(targetEntity: Tags::class)]
    #[Groups(groups: ['posts.show'])]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->published_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->is_published;
    }

    public function setIsPublished(bool $is_published): self
    {
        $this->is_published = $is_published;

        return $this;
    }

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function setAuthor(?Users $author): self
    {
        $this->author = $author;

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

    public function __toString(): string
    {
        return (string) $this->title;
    }

    private array $english_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    private array $french_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private array $english_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    private array $french_months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    #[Groups(groups: ['posts.show'])]
    public function getPublishedAtLocal(string $format = 'l j F Y'): string
    {
        return str_replace(
            $this->english_months,
            $this->french_months,
            str_replace(
                $this->english_days,
                $this->french_days,
                $this->published_at->format($format)
            )
        );
    }
}
