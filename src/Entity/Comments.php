<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tutos::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutos;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commented_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_validate;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    public function __construct()
    {
        $this->commented_at = new \DateTime();
        $this->is_validate = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTutos(): ?Tutos
    {
        return $this->tutos;
    }

    public function setTutos(?Tutos $tutos): self
    {
        $this->tutos = $tutos;

        return $this;
    }

    public function getCommentedAt(): ?\DateTimeInterface
    {
        return $this->commented_at;
    }

    public function setCommentedAt(\DateTimeInterface $commented_at): self
    {
        $this->commented_at = $commented_at;

        return $this;
    }

    public function getIsValidate(): ?bool
    {
        return $this->is_validate;
    }

    public function setIsValidate(bool $is_validate): self
    {
        $this->is_validate = $is_validate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
