<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class YoutubeLinks extends Links
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $youtube_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_small;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_medium;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_large;

    /**
     * @return mixed
     */
    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    /**
     * @param mixed $youtube_id
     *
     * @return YoutubeLinks
     */
    public function setYoutubeId($youtube_id)
    {
        $this->youtube_id = $youtube_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgSmall()
    {
        return $this->img_small;
    }

    /**
     * @param mixed $img_small
     *
     * @return YoutubeLinks
     */
    public function setImgSmall($img_small)
    {
        $this->img_small = $img_small;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgMedium()
    {
        return $this->img_medium;
    }

    /**
     * @param mixed $img_medium
     *
     * @return YoutubeLinks
     */
    public function setImgMedium($img_medium)
    {
        $this->img_medium = $img_medium;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgLarge()
    {
        return $this->img_large;
    }

    /**
     * @param mixed $img_large
     *
     * @return YoutubeLinks
     */
    public function setImgLarge($img_large)
    {
        $this->img_large = $img_large;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->getTitle());
    }

    #[Pure]
 public function __toString(): string
 {
     return (string) $this->getTitle();
 }
}
