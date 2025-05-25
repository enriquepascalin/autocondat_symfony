<?php

namespace App\Entity\WorkflowModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\NotificationModule\Notification;
use App\Entity\ProjectModule\Project;
use App\Repository\WorkflowModule\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource]
#[Broadcast]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkflowExecution $workflowExecution = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Actor $assignee = null;

    #[ORM\Column(enumType: TaskStatusEnum::class)]
    private ?TaskStatusEnum $status = null;

    #[ORM\Column(enumType: TaskPriorityEnum::class)]
    private ?TaskPriorityEnum $priority = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dueDate = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $projectTask = null;

    /**
     * @var Collection<int, Deadline>
     */
    #[ORM\OneToMany(targetEntity: Deadline::class, mappedBy: 'task')]
    private Collection $deadlines;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'task')]
    private Collection $notifications;

    /**
     * @var Collection<int, CalendarEvent>
     */
    #[ORM\OneToMany(targetEntity: CalendarEvent::class, mappedBy: 'task')]
    private Collection $events;

    public function __construct()
    {
        $this->deadlines = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkflowExecution(): ?WorkflowExecution
    {
        return $this->workflowExecution;
    }

    public function setWorkflowExecution(?WorkflowExecution $workflowExecution): static
    {
        $this->workflowExecution = $workflowExecution;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAssignee(): ?Actor
    {
        return $this->assignee;
    }

    public function setAssignee(?Actor $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TaskStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?TaskPriorityEnum
    {
        return $this->priority;
    }

    public function setPriority(TaskPriorityEnum $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTime $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getProjectTask(): ?Project
    {
        return $this->projectTask;
    }

    public function setProjectTask(?Project $projectTask): static
    {
        $this->projectTask = $projectTask;

        return $this;
    }

    /**
     * @return Collection<int, Deadline>
     */
    public function getDeadlines(): Collection
    {
        return $this->deadlines;
    }

    public function addDeadline(Deadline $deadline): static
    {
        if (!$this->deadlines->contains($deadline)) {
            $this->deadlines->add($deadline);
            $deadline->setTask($this);
        }

        return $this;
    }

    public function removeDeadline(Deadline $deadline): static
    {
        if ($this->deadlines->removeElement($deadline)) {
            // set the owning side to null (unless already changed)
            if ($deadline->getTask() === $this) {
                $deadline->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setTask($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getTask() === $this) {
                $notification->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CalendarEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(CalendarEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setTask($this);
        }

        return $this;
    }

    public function removeEvent(CalendarEvent $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getTask() === $this) {
                $event->setTask(null);
            }
        }

        return $this;
    }
}
