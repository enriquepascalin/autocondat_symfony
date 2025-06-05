<?php

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\WorkflowModule\Workflow;
use App\Repository\SubscriptionModule\FeatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Contracts\BlameableInterface;
use App\Contracts\TimestampableInterface;
use App\Contracts\SoftDeletableInterface;
use App\Contracts\TenantAwareInterface;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: FeatureRepository::class)]
#[ApiResource]
#[Broadcast]
class Feature implements TimestampableInterface, SoftDeletableInterface, TenantAwareInterface, BlameableInterface
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'childs')]
    private Collection $parent;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $childs;

    #[ORM\Column]
    private array $technicalMetadata = [];

    #[ORM\Column]
    private array $commercialMetadata = [];

    #[ORM\Column(enumType: FeatureTypeEnum::class)]
    private ?FeatureTypeEnum $type = null;

    #[ORM\Column]
    private ?bool $isCore = null;

    #[ORM\Column]
    private ?bool $isMarketplaceItem = null;

    /**
     * @var Collection<int, Workflow>
     */
    #[ORM\OneToMany(targetEntity: Workflow::class, mappedBy: 'feature')]
    private Collection $workflows;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
        $this->childs = new ArrayCollection();
        $this->workflows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    /**
     * @return Collection<int, self>
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }

    public function addParent(self $parent): static
    {
        if (!$this->parent->contains($parent)) {
            $this->parent->add($parent);
        }

        return $this;
    }

    public function removeParent(self $parent): static
    {
        $this->parent->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChilds(): Collection
    {
        return $this->childs;
    }

    public function addChild(self $child): static
    {
        if (!$this->childs->contains($child)) {
            $this->childs->add($child);
            $child->addParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->childs->removeElement($child)) {
            $child->removeParent($this);
        }

        return $this;
    }

    public function getTechnicalMetadata(): array
    {
        return $this->technicalMetadata;
    }

    public function setTechnicalMetadata(array $technicalMetadata): static
    {
        $this->technicalMetadata = $technicalMetadata;

        return $this;
    }

    public function getCommercialMetadata(): array
    {
        return $this->commercialMetadata;
    }

    public function setCommercialMetadata(array $commercialMetadata): static
    {
        $this->commercialMetadata = $commercialMetadata;

        return $this;
    }

    public function getType(): ?FeatureTypeEnum
    {
        return $this->type;
    }

    public function setType(FeatureTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isCore(): ?bool
    {
        return $this->isCore;
    }

    public function setIsCore(bool $isCore): static
    {
        $this->isCore = $isCore;

        return $this;
    }

    public function isMarketplaceItem(): ?bool
    {
        return $this->isMarketplaceItem;
    }

    public function setIsMarketplaceItem(bool $isMarketplaceItem): static
    {
        $this->isMarketplaceItem = $isMarketplaceItem;

        return $this;
    }

    /**
     * @return Collection<int, Workflow>
     */
    public function getWorkflows(): Collection
    {
        return $this->workflows;
    }

    public function addWorkflow(Workflow $workflow): static
    {
        if (!$this->workflows->contains($workflow)) {
            $this->workflows->add($workflow);
            $workflow->setFeature($this);
        }

        return $this;
    }

    public function removeWorkflow(Workflow $workflow): static
    {
        if ($this->workflows->removeElement($workflow)) {
            // set the owning side to null (unless already changed)
            if ($workflow->getFeature() === $this) {
                $workflow->setFeature(null);
            }
        }

        return $this;
    }
}
