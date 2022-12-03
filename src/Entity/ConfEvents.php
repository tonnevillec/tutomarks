<?php

namespace App\Entity;

use App\Repository\ConfEventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfEventsRepository::class)]
class ConfEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conferences $conference = null;

    #[ORM\OneToMany(mappedBy: 'confEvent', targetEntity: ConfEventPlaylist::class, orphanRemoval: true)]
    private Collection $playlist;

    public function __construct()
    {
        $this->playlist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getConference(): ?Conferences
    {
        return $this->conference;
    }

    public function setConference(?Conferences $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    /**
     * @return Collection<int, ConfEventPlaylist>
     */
    public function getPlaylist(): Collection
    {
        return $this->playlist;
    }

    public function addPlaylist(ConfEventPlaylist $playlist): self
    {
        if (!$this->playlist->contains($playlist)) {
            $this->playlist->add($playlist);
            $playlist->setConfEvent($this);
        }

        return $this;
    }

    public function removePlaylist(ConfEventPlaylist $playlist): self
    {
        if ($this->playlist->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getConfEvent() === $this) {
                $playlist->setConfEvent(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->year;
    }
}
