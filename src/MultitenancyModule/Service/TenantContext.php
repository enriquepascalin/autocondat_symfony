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

namespace App\MultitenancyModule\Service;

use App\MultitenancyModule\Entity\Tenant;
use Symfony\Bundle\SecurityBundle\Security;

class TenantContext
{
    /**
     * Current tenant instance.
     */
    private ?Tenant $tenant = null;


    /**
     * Flag indicating if tenant has been initialized.
     */
    private bool $initialized = false;

    /**
     * Constructor.
     *
     * @param Security $security Symfony security service
     */
    public function __construct(
        private readonly Security $security,
    ) {
        $this->initializeTenant();
    }

    /**
     * Initialize tenant from authenticated user.
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
     * Get current tenant.
     *
     * @return Tenant|null Current tenant or null if not available
     */
    public function getCurrentTenant(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Set current tenant.
     *
     * @param Tenant $tenant Tenant instance to set
     *
     * @throws \LogicException If tenant already initialized
     */
    public function setCurrentTenant(?Tenant $tenant): void
    {
        if ($this->initialized) {
            throw new \LogicException('Tenant already initialized from security context');
        }
        $this->tenant = $tenant;
        $this->initialized = true;
    }

    /**
     * Check if tenant context has been initialized.
     *
     * @return bool True if initialized, false otherwise
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }
}
