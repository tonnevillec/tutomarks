<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Le compte existe déjà"
 * )
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_actif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_connection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Links::class, mappedBy="published_by")
     */
    private $links;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @var string|null
     * @Assert\EqualTo(propertyPath="password", message="Le mot de passe ne correspond pas")
     */
    private $password_repeat;

    /**
     * @var string|null
     */
    private $password_confirm;

    /**
     * @var string|null
     * @Assert\EqualTo(propertyPath="email", message="Le mail ne correspond pas")
     */
    private $email_repeat;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->last_connection = new \DateTime();
        $this->is_actif = true;
        $this->roles = ['ROLE_USER'];
        $this->links = new ArrayCollection();
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
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

    public function setLastConnection(\DateTimeInterface $last_connection): self
    {
        $this->last_connection = $last_connection;

        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return $this->username ?: $this->email;
    }

    /**
     * @return Collection|Links[]
     */
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

    /**
     * @return mixed
     */
    public function getGithubId()
    {
        return $this->githubId;
    }

    /**
     * @param mixed $githubId
     * @return Users
     */
    public function setGithubId($githubId)
    {
        $this->githubId = $githubId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     * @return Users
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
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
     * @return Users
     */
    public function setPasswordRepeat(?string $password_repeat): Users
    {
        $this->password_repeat = $password_repeat;
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
     * @return Users
     */
    public function setPasswordConfirm(?string $password_confirm): Users
    {
        $this->password_confirm = $password_confirm;
        return $this;
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
     * @return Users
     */
    public function setEmailRepeat(?string $email_repeat): Users
    {
        $this->email_repeat = $email_repeat;
        return $this;
    }
}
