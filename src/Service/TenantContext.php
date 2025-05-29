<?php

namespace App\Service;

use App\Entity\MultitenancyModule\Tenant;
use Symfony\Bundle\SecurityBundle\Security;
use LogicException;

class TenantContext
{
    /**
     * Current tenant instance
     * @var Tenant|null
     */
    private ?Tenant $tenant = null;
    
    
    /**
     * Flag indicating if tenant has been initialized
     * @var bool
     */
    private bool $initialized = false;

    /**
     * Constructor
     * 
     * @param Security $security Symfony security service
     */
    public function __construct(
        private readonly Security $security
    )
    {
        $this->initializeTenant();
    }

    /**
     * Initialize tenant from authenticated user
     */
    private function initializeTenant(): void
    {
        $user = $this->security->getUser();
        if ($user && method_exists($user, 'getTenant')) {
            $this->tenant = $user->getTenant();
            $this->initialized = true;
        }
    }

    /**
     * Get current tenant
     * 
     * @return Tenant|null Current tenant or null if not available
     */
    public function getCurrentTenant(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Set current tenant
     * 
     * @param Tenant $tenant Tenant instance to set
     * @throws LogicException If tenant already initialized
     */
    public function setCurrentTenant(?Tenant $tenant): void
    {
        if ($this->initialized) {
            throw new LogicException('Tenant already initialized from security context');
        } 
        $this->tenant = $tenant;
        $this->initialized = true;
    }

    /**
     * Check if tenant context has been initialized
     * 
     * @return bool True if initialized, false otherwise
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }
}
