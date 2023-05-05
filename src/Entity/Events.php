<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    #[Groups(groups: ['show_events'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(groups: ['show_events'])]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Authors::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['show_events'])]
    private Authors $author;

    #[ORM\Column(type: 'datetime')]
    #[Groups(groups: ['show_events'])]
    private ?\DateTimeInterface $started_at;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Url]
    #[Groups(groups: ['show_events'])]
    private ?string $url = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(groups: ['show_events'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(groups: ['show_events'])]
    private ?\DateTimeInterface $published_at;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private Users $published_by;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['show_events'])]
    private ?bool $live_on_twitch = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['show_events'])]
    private ?bool $live_on_youtube = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['show_events'])]
    private ?bool $live_on_twitter = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['show_events'])]
    private ?bool $is_physical = true;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: ['show_events'])]
    private ?bool $is_free = true;

    public function __construct()
    {
        $this->started_at = new \DateTimeImmutable();
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

    public function getAuthor(): ?Authors
    {
        return $this->author;
    }

    public function setAuthor(?Authors $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getPublishedBy(): ?Users
    {
        return $this->published_by;
    }

    public function setPublishedBy(?Users $published_by): self
    {
        $this->published_by = $published_by;

        return $this;
    }

    public function getLiveOnTwitch(): ?bool
    {
        return $this->live_on_twitch;
    }

    public function setLiveOnTwitch(bool $live_on_twitch): self
    {
        $this->live_on_twitch = $live_on_twitch;

        return $this;
    }

    public function getLiveOnYoutube(): ?bool
    {
        return $this->live_on_youtube;
    }

    public function setLiveOnYoutube(bool $live_on_youtube): self
    {
        $this->live_on_youtube = $live_on_youtube;

        return $this;
    }

    public function getLiveOnTwitter(): ?bool
    {
        return $this->live_on_twitter;
    }

    public function setLiveOnTwitter(bool $live_on_twitter): self
    {
        $this->live_on_twitter = $live_on_twitter;

        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->is_free;
    }

    public function setIsFree(bool $is_free): self
    {
        $this->is_free = $is_free;

        return $this;
    }

    public function getIsPhysical(): ?bool
    {
        return $this->is_physical;
    }

    public function setIsPhysical(bool $is_physical): self
    {
        $this->is_physical = $is_physical;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    private array $english_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    private array $french_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private array $english_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    private array $french_months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    #[Groups(groups: ['show_events'])]
    public function getStartedAtLocal(string $format = 'l j F Y'): string
    {
        return str_replace(
            $this->english_months,
            $this->french_months,
            str_replace(
                $this->english_days,
                $this->french_days,
                $this->started_at->format($format)
            )
        );
    }
}
