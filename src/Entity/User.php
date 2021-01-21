<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Le compte existe déjà"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Groups({"read:comment"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $password;

    /**
     * @var string|null
     * @Assert\EqualTo(propertyPath="password", message="Le mot de passe ne correspond pas")
     */
    private $password_repeat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_actif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_connection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:comment"})
     */
    private $username;

    /**
     * @var string|null
     */
    private $password_confirm;

    /**
     * @var string|null
     * @Assert\EqualTo(propertyPath="email", message="Le mail ne correspond pas")
     */
    private $email_repeat;

    /**
     * @ORM\OneToMany(targetEntity=Tutos::class, mappedBy="published_by")
     */
    private $tutos;

    /**
     * @ORM\OneToMany(targetEntity=Evaluations::class, mappedBy="user")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @ORM\OneToMany(targetEntity=UserTutosInformations::class, mappedBy="user", orphanRemoval=true)
     */
    private $tutosInformations;


    public function __construct()
    {
        $this->is_actif = true;
        $this->created_at = new DateTime();
        $this->last_connection = new DateTime();
        $this->roles = ['ROLE_USER'];
        $this->tutos = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tutosInformations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasswordRepeat(): ?string
    {
        return $this->password_repeat;
    }

    /**
     * @param string|null $password_repeat
     */
    public function setPasswordRepeat(?string $password_repeat): void
    {
        $this->password_repeat = $password_repeat;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsActif(): ?bool
    {
        return $this->is_actif;
    }

    public function setIsActif(bool $is_actif): self
    {
        $this->is_actif = $is_actif;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->last_connection;
    }

    public function setLastConnection(?\DateTimeInterface $last_connection): self
    {
        $this->last_connection = $last_connection;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasswordConfirm(): ?string
    {
        return $this->password_confirm;
    }

    /**
     * @param string|null $password_confirm
     */
    public function setPasswordConfirm(?string $password_confirm): void
    {
        $this->password_confirm = $password_confirm;
    }

    /**
     * @return string|null
     */
    public function getEmailRepeat(): ?string
    {
        return $this->email_repeat;
    }

    /**
     * @param string|null $email_repeat
     */
    public function setEmailRepeat(?string $email_repeat): void
    {
        $this->email_repeat = $email_repeat;
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
            $tuto->setPublishedBy($this);
        }

        return $this;
    }

    public function removeTuto(Tutos $tuto): self
    {
        if ($this->tutos->contains($tuto)) {
            $this->tutos->removeElement($tuto);
            // set the owning side to null (unless already changed)
            if ($tuto->getPublishedBy() === $this) {
                $tuto->setPublishedBy(null);
            }
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

    public function addEvaluation(Evaluations $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setUser($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getUser() === $this) {
                $evaluation->setUser(null);
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->username ?? $this->email;
    }

    public function getGithubId(): ?string
    {
        return $this->githubId;
    }

    public function setGithubId(?string $githubId): self
    {
        $this->githubId = $githubId;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection|UserTutosInformations[]
     */
    public function getTutosInformations(): Collection
    {
        return $this->tutosInformations;
    }

    public function addTutosInformation(UserTutosInformations $tutosInformation): self
    {
        if (!$this->tutosInformations->contains($tutosInformation)) {
            $this->tutosInformations[] = $tutosInformation;
            $tutosInformation->setUser($this);
        }

        return $this;
    }

    public function removeTutosInformation(UserTutosInformations $tutosInformation): self
    {
        if ($this->tutosInformations->removeElement($tutosInformation)) {
            // set the owning side to null (unless already changed)
            if ($tutosInformation->getUser() === $this) {
                $tutosInformation->setUser(null);
            }
        }

        return $this;
    }
}
