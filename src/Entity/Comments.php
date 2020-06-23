<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 * @ApiResource (
 *      attributes = {
 *          "order"={"commented_at":"DESC"},
 *      },
 *     paginationItemsPerPage=5,
 *     normalizationContext={"groups"={"read:comment"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "controller"=App\Controller\Api\CommentCreateController::class,
 *              "denormalization_context"={"groups"={"create:comment"}}
 *          }
 *      },
 *     itemOperations={
 *         "get"={
 *             "controller"=NotFoundAction::class,
 *             "read"=false,
 *             "output"=false,
 *         },
 *     }
 * )
 * @ApiFilter (
 *     SearchFilter::class,
 *     properties={"tutos": "exact"}
 * )
 * @ApiFilter (
 *     BooleanFilter::class,
 *     properties={"is_validate"}
 * )
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:comment"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:comment", "create:comment"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tutos::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:comment", "create:comment"})
     */
    private $tutos;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:comment"})
     */
    private $commented_at;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:comment"})
     */
    private $is_validate;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="10", minMessage="Veuillez saisir au moins 10 caractères")
     * @Groups({"read:comment", "create:comment"})
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
