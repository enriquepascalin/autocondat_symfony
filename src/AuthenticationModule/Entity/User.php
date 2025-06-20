<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders.  If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 *
 * @author Enrique Pascalin, Erparom Technologies
 *
 * @version 1.0.0
 *
 * @since 2025-06-01
 *
 * @license license.md
 */

declare(strict_types=1);

namespace App\AuthenticationModule\Entity;

use App\MultitenancyModule\Entity\Segment;
use App\AuthenticationModule\Entity\RolesEnum;
use App\NotificationModule\Entity\Audience;
use App\ProjectModule\Entity\ProjectPhase;
use App\ProjectModule\Entity\ProjectPhaseAssignment;
use App\SubscriptionModule\Entity\License;
use App\SupportModule\Entity\Ticket;
use App\AuthenticationModule\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Contracts\BlameableInterface;
use App\Contracts\TimestampableInterface;
use App\Contracts\SoftDeletableInterface;
use App\Contracts\TenantAwareInterface;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TimestampableInterface, SoftDeletableInterface, TenantAwareInterface, BlameableInterface
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The email address of the user
     */
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
     * @var array<string>
     */
      #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var RolesEnum|null 
     */
    #[ORM\Column(enumType: RolesEnum::class)]
    private ?RolesEnum $role = null;

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

    #[ORM\Column(nullable: true)]
    private ?array $mfaBackupCodes = null;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $tickets;

    /**
     * @var Collection<int, License>
     */
    #[ORM\OneToMany(targetEntity: License::class, mappedBy: 'assignedUser')]
    private Collection $licenses;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $locale = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(enumType: UserStatusEnum::class)]
    private ?UserStatusEnum $status = null;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->consentLogs = new ArrayCollection();
        $this->segments = new ArrayCollection();
        $this->asignedProjectPhases = new ArrayCollection();
        $this->projectPhaseAssignments = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->licenses = new ArrayCollection();
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

    public function getMfaBackupCodes(): ?array
    {
        return $this->mfaBackupCodes;
    }

    public function setMfaBackupCodes(?array $mfaBackupCodes): static
    {
        $this->mfaBackupCodes = $mfaBackupCodes;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setOwner($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getOwner() === $this) {
                $ticket->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, License>
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function addLicense(License $license): static
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses->add($license);
            $license->setAssignedUser($this);
        }

        return $this;
    }

    public function removeLicense(License $license): static
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getAssignedUser() === $this) {
                $license->setAssignedUser(null);
            }
        }

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

        public function getRole(): ?RolesEnum
    {
        if ($this->role === null) {
            $this->role = RolesEnum::USER;
        }
        return $this->role;
    }

    public function setRole(RolesEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getStatus(): ?UserStatusEnum
    {
        return $this->status;
    }

    public function setStatus(UserStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
