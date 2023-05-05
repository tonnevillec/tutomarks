<?php

namespace App\Entity;

use App\Repository\AuthorsRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuthorsRepository::class)]
class Authors
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    #[Groups(groups: ['show_events'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(groups: ['show_events'])]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(groups: ['show_events'])]
    private ?string $logo = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $site_url = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $twitter = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $twitch = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $youtube = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Links::class)]
    private Collection $links;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $yt_logo;

    #[ORM\OneToOne(targetEntity: Attachments::class, cascade: ['persist', 'remove'])]
    private ?Attachments $attachment;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Events::class)]
    private Collection $events;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->events = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getYtLogo(): ?string
    {
        return $this->yt_logo;
    }

    public function setYtLogo(?string $yt_logo): self
    {
        $this->yt_logo = $yt_logo;

        return $this;
    }

    public function getSiteUrl(): ?string
    {
        return $this->site_url;
    }

    public function setSiteUrl(?string $site_url): self
    {
        $this->site_url = $site_url;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(?string $twitch): self
    {
        $this->twitch = $twitch;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Links $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setAuthor($this);
        }

        return $this;
    }

    public function removeLink(Links $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getAuthor() === $this) {
                $link->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): Authors
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAttachment(): ?Attachments
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachments $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->getTitle());
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAuthor($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAuthor() === $this) {
                $event->setAuthor(null);
            }
        }

        return $this;
    }
}
