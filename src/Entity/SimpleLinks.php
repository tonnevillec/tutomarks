<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable()
 */
class SimpleLinks extends Links
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="simple_links_images", fileNameProperty="image")
     * @var ?File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $updatedAt;

    public function __construct()
    {
        parent::__construct();

        $this->updatedAt = new DateTime();
        $this->imageFile = null;
        $this->image = '';
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return SimpleLinks
     */
    public function setImage(?string $image): SimpleLinks
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return SimpleLinks
     */
    public function setUpdatedAt(?DateTime $updatedAt): SimpleLinks
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return (string) $this->getTitle();
    }
}