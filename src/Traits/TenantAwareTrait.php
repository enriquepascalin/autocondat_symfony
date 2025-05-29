<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\MultitenancyModule\Tenant;

trait TenantAwareTrait
{
    #[ORM\ManyToOne(targetEntity: Tenant::class)]
    #[ORM\JoinColumn(name: 'tenant_id', nullable: true, onDelete: 'SET NULL')]
    private ?Tenant $tenant = null;

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }
}