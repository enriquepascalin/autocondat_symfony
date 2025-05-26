<?php

namespace App\Entity\AuthenticationModule;

use App\Entity\MultitenancyModule\Segment;
use App\Entity\NotificationModule\Audience;
use App\Entity\ProjectModule\ProjectPhase;
use App\Entity\ProjectModule\ProjectPhaseAssignment;
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

    /**
     * @var Collection<int, ProjectPhase>
     */
    #[ORM\ManyToMany(targetEntity: ProjectPhase::class, mappedBy: 'assignees')]
    private Collection $asignedProjectPhases;

    /**
     * @var Collection<int, ProjectPhaseAssignment>
     */
    #[ORM\OneToMany(targetEntity: ProjectPhaseAssignment::class, mappedBy: 'autocondatUser')]
    private Collection $projectPhaseAssignments;

    /**
     * @var Collection<int, Audience>
     */
    #[ORM\ManyToMany(targetEntity: Audience::class, mappedBy: 'users')]
    private Collection $audiences;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mfaSecret = null;

    #[ORM\Column]
    private ?bool $isMfaEnabled = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passwordResetToken = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $passwordResetExpiresAt = null;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->consentLogs = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->segments = new ArrayCollection();
        $this->asignedProjectPhases = new ArrayCollection();
        $this->projectPhaseAssignments = new ArrayCollection();
        $this->audiences = new ArrayCollection();
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

    /**
     * @return Collection<int, ProjectPhase>
     */
    public function getAsignedProjectPhases(): Collection
    {
        return $this->asignedProjectPhases;
    }

    public function addAsignedProjectPhase(ProjectPhase $asignedProjectPhase): static
    {
        if (!$this->asignedProjectPhases->contains($asignedProjectPhase)) {
            $this->asignedProjectPhases->add($asignedProjectPhase);
            $asignedProjectPhase->addAssignee($this);
        }

        return $this;
    }

    public function removeAsignedProjectPhase(ProjectPhase $asignedProjectPhase): static
    {
        if ($this->asignedProjectPhases->removeElement($asignedProjectPhase)) {
            $asignedProjectPhase->removeAssignee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectPhaseAssignment>
     */
    public function getProjectPhaseAssignments(): Collection
    {
        return $this->projectPhaseAssignments;
    }

    public function addProjectPhaseAssignment(ProjectPhaseAssignment $projectPhaseAssignment): static
    {
        if (!$this->projectPhaseAssignments->contains($projectPhaseAssignment)) {
            $this->projectPhaseAssignments->add($projectPhaseAssignment);
            $projectPhaseAssignment->setAutocondatUser($this);
        }

        return $this;
    }

    public function removeProjectPhaseAssignment(ProjectPhaseAssignment $projectPhaseAssignment): static
    {
        if ($this->projectPhaseAssignments->removeElement($projectPhaseAssignment)) {
            // set the owning side to null (unless already changed)
            if ($projectPhaseAssignment->getAutocondatUser() === $this) {
                $projectPhaseAssignment->setAutocondatUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Audience>
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): static
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences->add($audience);
            $audience->addUser($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): static
    {
        if ($this->audiences->removeElement($audience)) {
            $audience->removeUser($this);
        }

        return $this;
    }

    public function getMfaSecret(): ?string
    {
        return $this->mfaSecret;
    }

    public function setMfaSecret(?string $mfaSecret): static
    {
        $this->mfaSecret = $mfaSecret;

        return $this;
    }

    public function isMfaEnabled(): ?bool
    {
        return $this->isMfaEnabled;
    }

    public function setIsMfaEnabled(bool $isMfaEnabled): static
    {
        $this->isMfaEnabled = $isMfaEnabled;

        return $this;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): static
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    public function getPasswordResetExpiresAt(): ?\DateTimeImmutable
    {
        return $this->passwordResetExpiresAt;
    }

    public function setPasswordResetExpiresAt(?\DateTimeImmutable $passwordResetExpiresAt): static
    {
        $this->passwordResetExpiresAt = $passwordResetExpiresAt;

        return $this;
    }
}
