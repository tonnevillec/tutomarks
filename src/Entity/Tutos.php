<?php

namespace App\Entity;

use App\Repository\TutosRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=TutosRepository::class)
 * @UniqueEntity(
 *     fields={"url"},
 *     message="L'url saisie existe déjà"
 * )
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *          "get"={
 *              "controller"=App\Controller\Api\EmptyController::class,
 *              "read"=false,
 *              "deserialize"=false
 *          }
 *      }
 * )
 */
class Tutos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tutos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $published_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="4", minMessage="Le titre doit faire au moins 8 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "l'url '{{ value }}' n'est pas valide")
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Evaluations::class, mappedBy="tutos")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="tutos")
     */
    private $comments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="tutos")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Langues::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $langue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $moy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=Prices::class)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Levels::class)
     */
    private $level;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->published_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublishedBy(): ?User
    {
        return $this->published_by;
    }

    public function setPublishedBy(?User $published_by): self
    {
        $this->published_by = $published_by;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
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

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getSlug()
    {
        return (new Slugify())->slugify($this->getTitle());
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

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addTuto($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeTuto($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evaluations[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    /**
     * @return mixed
     */
    public function getMoy()
    {
        return $this->moy;
    }

    /**
     * @param mixed $moy
     * @return Tutos
     */
    public function setMoy($moy)
    {
        $this->moy = $moy;
        return $this;
    }

    public function addEvaluation(Evaluations $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setTutos($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getTutos() === $this) {
                $evaluation->setTutos(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTutos($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTutos() === $this) {
                $comment->setTutos(null);
            }
        }

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

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLangue(): ?Langues
    {
        return $this->langue;
    }

    public function setLangue(?Langues $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function __toString():string
    {
        return $this->title;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?Prices
    {
        return $this->price;
    }

    public function setPrice(?Prices $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLevel(): ?Levels
    {
        return $this->level;
    }

    public function setLevel(?Levels $level): self
    {
        $this->level = $level;

        return $this;
    }
}
