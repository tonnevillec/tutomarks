<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 * @Vich\Uploadable()
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Tutos::class, mappedBy="category")
     */
    private $tutos;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $homekey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $withVideos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="categories_images", fileNameProperty="image")
     * @var File|null
     */
    private ?File $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $updatedAt;

    public function __construct()
    {
        $this->tutos = new ArrayCollection();
        $this->withVideos = false;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomekey()
    {
        return $this->homekey;
    }

    /**
     * @param mixed $homekey
     * @return Categories
     */
    public function setHomekey($homekey)
    {
        $this->homekey = $homekey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWithVideos()
    {
        return $this->withVideos;
    }

    /**
     * @param mixed $withVideos
     * @return Categories
     */
    public function setWithVideos($withVideos)
    {
        $this->withVideos = $withVideos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function hasVideos()
    {
        return $this->withVideos;
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
            $tuto->setCategory($this);
        }

        return $this;
    }

    public function removeTuto(Tutos $tuto): self
    {
        if ($this->tutos->contains($tuto)) {
            $this->tutos->removeElement($tuto);
            // set the owning side to null (unless already changed)
            if ($tuto->getCategory() === $this) {
                $tuto->setCategory(null);
            }
        }

        return $this;
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
     * @return Tags
     */
    public function setImage(?string $image): self
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

    public function setImageFile(File $image = null): self
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->title;
    }
}
