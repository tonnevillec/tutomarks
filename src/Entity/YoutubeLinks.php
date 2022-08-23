<?php

namespace App\Entity;

use App\Repository\YoutubeLinksRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YoutubeLinksRepository::class)]
class YoutubeLinks extends Links
{
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $youtube_id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $img_small = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $img_medium = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $img_large = null;

    public function __construct()
    {
        parent::__construct();
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

    public function getImgSmall(): ?string
    {
        return $this->img_small;
    }

    public function setImgSmall(?string $img_small): self
    {
        $this->img_small = $img_small;

        return $this;
    }

    public function getImgMedium(): ?string
    {
        return $this->img_medium;
    }

    public function setImgMedium(?string $img_medium): self
    {
        $this->img_medium = $img_medium;

        return $this;
    }

    public function getImgLarge(): ?string
    {
        return $this->img_large;
    }

    public function setImgLarge(?string $img_large): self
    {
        $this->img_large = $img_large;

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
}
