<?php

namespace App\Entity;

use App\Repository\LanguagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=LanguagesRepository::class)
 * @Vich\Uploadable()
 */
class Languages
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
    private $name;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $shortname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Links::class, mappedBy="language")
     */
    private $links;

    /**
     * @Vich\UploadableField(mapping="languages_images", fileNameProperty="logo")
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
        $this->links = new ArrayCollection();
        $this->updatedAt = new DateTime();
        $this->imageFile = null;
        $this->logo = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

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

    /**
     * @return ArrayCollection|null
     */
    public function getLinks(): ?ArrayCollection
    {
        return $this->links;
    }

    /**
     * @param ArrayCollection $links
     */
    public function setLinks(ArrayCollection $links): void
    {
        $this->links = $links;
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
     * @return Tags
     */
    public function setUpdatedAt(?DateTime $updatedAt): Tags
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    #[Pure] public function __toString(): string
    {
        return (string) $this->getName();
    }
}
