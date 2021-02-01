<?php

namespace App\Entity;

use App\Repository\ChannelsRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChannelsRepository::class)
 */
class Channels
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnails_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube_custom_url;

    /**
     * @ORM\OneToMany(targetEntity=Tutos::class, mappedBy="channel")
     */
    private $tutos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $github;

    public function __construct()
    {
        $this->tutos = new ArrayCollection();
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

    public function getThumbnailsUrl(): ?string
    {
        return $this->thumbnails_url;
    }

    public function setThumbnailsUrl(?string $thumbnails_url): self
    {
        $this->thumbnails_url = $thumbnails_url;

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

    public function getYoutubeId(): ?string
    {
        return $this->youtube_id;
    }

    public function setYoutubeId(?string $youtube_id): self
    {
        $this->youtube_id = $youtube_id;

        return $this;
    }

    public function getYoutubeCustomUrl(): ?string
    {
        return $this->youtube_custom_url;
    }

    public function setYoutubeCustomUrl(?string $youtube_custom_url): self
    {
        $this->youtube_custom_url = $youtube_custom_url;

        return $this;
    }

    /**
     * @return Collection|Tutos[]
     */
    public function getTutos(): Collection
    {
        return $this->tutos;
    }

    public function addTuto(Tutos $tuto): self
    {
        if (!$this->tutos->contains($tuto)) {
            $this->tutos[] = $tuto;
            $tuto->setChannel($this);
        }

        return $this;
    }

    public function removeTuto(Tutos $tuto): self
    {
        if ($this->tutos->removeElement($tuto)) {
            // set the owning side to null (unless already changed)
            if ($tuto->getChannel() === $this) {
                $tuto->setChannel(null);
            }
        }

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->getTitle());
    }

    public function __toString():string
    {
        return $this->title;
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
}
