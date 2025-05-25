<?php

namespace App\Entity\AuthenticationModule;

use App\Entity\MultitenancyModule\Segment;
use App\Repository\AuthenticationModule\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'autocondatUser', orphanRemoval: true)]
    private Collection $sessions;

    /**
     * @var Collection<int, ConsentLog>
     */
    #[ORM\OneToMany(targetEntity: ConsentLog::class, mappedBy: 'autocondatUser', orphanRemoval: true)]
    private Collection $consentLogs;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    /**
     * @var Collection<int, Segment>
     */
    #[ORM\ManyToMany(targetEntity: Segment::class, inversedBy: 'users')]
    private Collection $segments;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->consentLogs = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->segments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setAutocondatUser($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getAutocondatUser() === $this) {
                $session->setAutocondatUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConsentLog>
     */
    public function getConsentLogs(): Collection
    {
        return $this->consentLogs;
    }

    public function addConsentLog(ConsentLog $consentLog): static
    {
        if (!$this->consentLogs->contains($consentLog)) {
            $this->consentLogs->add($consentLog);
            $consentLog->setAutocondatUser($this);
        }

        return $this;
    }

    public function removeConsentLog(ConsentLog $consentLog): static
    {
        if ($this->consentLogs->removeElement($consentLog)) {
            // set the owning side to null (unless already changed)
            if ($consentLog->getAutocondatUser() === $this) {
                $consentLog->setAutocondatUser(null);
            }
        }

        return $this;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, Segment>
     */
    public function getSegments(): Collection
    {
        return $this->segments;
    }

    public function addSegment(Segment $segment): static
    {
        if (!$this->segments->contains($segment)) {
            $this->segments->add($segment);
        }

        return $this;
    }

    public function removeSegment(Segment $segment): static
    {
        $this->segments->removeElement($segment);

        return $this;
    }
}
