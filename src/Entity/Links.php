<?php

namespace App\Entity;

use App\Repository\LinksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinksRepository::class)
 * @ORM\Table(
 *     name="links",
 *     indexes={
 *          @ORM\Index(columns={"title", "description"}, flags={"fulltext"})
 *     }
 * )
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *  "youtube" = "YoutubeLinks",
 *  "simple" = "SimpleLinks"
 * })
 */
abstract class Links
{
    public const LINK_ENTITY = ['simple', 'youtube'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $title = null;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTimeInterface $published_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $url = null;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     */
    protected ?Categories $category = null;

    /**
     * @ORM\ManyToOne(targetEntity=Authors::class, inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     */
    protected ?Authors $author = null;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="links")
     */
    protected Collection $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Languages::class, inversedBy="links")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?Languages $language = null;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $is_publish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Users $published_by = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->published_at = new \DateTime();
        $this->is_publish = false;
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

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
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

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Authors
    {
        return $this->author;
    }

    public function setAuthor(?Authors $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getLanguage(): ?Languages
    {
        return $this->language;
    }

    public function setLanguage(?Languages $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getIsPublish(): ?bool
    {
        return $this->is_publish;
    }

    public function setIsPublish(bool $is_publish): self
    {
        $this->is_publish = $is_publish;

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

    public function getPublishedBy(): ?Users
    {
        return $this->published_by;
    }

    public function setPublishedBy(?Users $published_by): self
    {
        $this->published_by = $published_by;

        return $this;
    }
}
