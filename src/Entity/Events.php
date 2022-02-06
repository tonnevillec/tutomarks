<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventsRepository::class)
 */
class Events
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\ManyToOne(targetEntity=Authors::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private Authors $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $started_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url
     */
    private ?string $url;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $published_at;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private Users $published_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $live_on_twitch;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $live_on_youtube;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $live_on_twitter;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $is_physical;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $is_free;

    public function __construct()
    {
        $this->started_at = new DateTime();
        $this->published_at = new DateTime();

        $this->is_free = true;
        $this->live_on_twitch = false;
        $this->live_on_twitter = false;
        $this->live_on_youtube = false;
        $this->is_physical = true;
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

    public function getStartedAt(): ?DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(DateTimeInterface $started_at): self
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

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(DateTimeInterface $published_at): self
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
}
