<?php

namespace App\Entity;

use App\Repository\ConcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConcoursRepository::class)]
class Concours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $isOpen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hashtag = null;

    #[ORM\Column(length: 255)]
    private ?string $postTwitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoYoutube = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endedAt = null;

    #[ORM\OneToMany(mappedBy: 'concours', targetEntity: ConcoursParticipants::class, orphanRemoval: true)]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->createdAt = new \DateTime();
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

    public function isIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    public function setHashtag(?string $hashtag): self
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    public function getPostTwitter(): ?string
    {
        return $this->postTwitter;
    }

    public function setPostTwitter(string $postTwitter): self
    {
        $this->postTwitter = $postTwitter;

        return $this;
    }

    public function getVideoYoutube(): ?string
    {
        return $this->videoYoutube;
    }

    public function setVideoYoutube(?string $videoYoutube): self
    {
        $this->videoYoutube = $videoYoutube;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeInterface $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * @return Collection<int, ConcoursParticipants>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(ConcoursParticipants $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setConcours($this);
        }

        return $this;
    }

    public function removeParticipant(ConcoursParticipants $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getConcours() === $this) {
                $participant->setConcours(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
