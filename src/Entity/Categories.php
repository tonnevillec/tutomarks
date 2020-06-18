<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
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

    public function __toString():string
    {
        return $this->title;
    }
}
