<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Le compte existe déjà')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    #[Groups(groups: ['posts.show'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    #[Groups(groups: ['posts.show'])]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 8, minMessage: 'Votre mot de passe doit faire au moins 8 caractères')]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_actif = true;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $created_at;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $last_connection;

    #[ORM\Column(name: 'username', type: 'string', length: 255, nullable: true)]
    #[Groups(groups: ['posts.show'])]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'published_by', targetEntity: Links::class)]
    private Collection $links;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $githubId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $googleId = null;

    #[Assert\EqualTo(propertyPath: 'password', message: 'Le mot de passe ne correspond pas')]
    private ?string $password_repeat = null;

    private ?string $password_confirm = null;

    #[Assert\EqualTo(propertyPath: 'email', message: 'Le mail ne correspond pas')]
    private ?string $email_repeat = null;

    #[ORM\OneToMany(mappedBy: 'published_by', targetEntity: Events::class)]
    private Collection $events;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->last_connection = new \DateTime();
        $this->roles = ['ROLE_USER'];
        $this->links = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): ?string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastConnection(): ?\DateTime
    {
        return $this->last_connection;
    }

    public function setLastConnection(\DateTime $last_connection): self
    {
        $this->last_connection = $last_connection;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username ?: $this->email;
    }

    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Links $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setPublishedBy($this);
        }

        return $this;
    }

    public function removeLink(Links $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getPublishedBy() === $this) {
                $link->setPublishedBy(null);
            }
        }

        return $this;
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

    public function getPasswordRepeat(): ?string
    {
        return $this->password_repeat;
    }

    public function setPasswordRepeat(?string $password_repeat): Users
    {
        $this->password_repeat = $password_repeat;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->password_confirm;
    }

    public function setPasswordConfirm(?string $password_confirm): Users
    {
        $this->password_confirm = $password_confirm;

        return $this;
    }

    public function getEmailRepeat(): ?string
    {
        return $this->email_repeat;
    }

    public function setEmailRepeat(?string $email_repeat): Users
    {
        $this->email_repeat = $email_repeat;

        return $this;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPublishedBy($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getPublishedBy() === $this) {
                $event->setPublishedBy(null);
            }
        }

        return $this;
    }
}
