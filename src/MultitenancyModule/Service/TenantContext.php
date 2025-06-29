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

use App\MultitenancyModule\Contract\TenantContextInterface;
use App\MultitenancyModule\Entity\Tenant;
use App\MultitenancyModule\Repository\TenantRepository;
use RuntimeException;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Default implementation of TenantContextInterface.
 * Manages the current tenant, initializing it from the authenticated user or by explicit setters.
 */
final class TenantContext implements TenantContextInterface
{
    /**
     * Current tenant instance.
     */
    private ?Tenant $tenant = null;

    /**
     * Flag indicating whether the context has been initialized.
     */
    private bool $initialized = false;

    public function __construct(
        private readonly Security $security,
        private readonly TenantRepository $tenantRepository
    ) {
        $this->initializeFromSecurity();
    }

    /**
     * {@inheritDoc}
     */
    public function loadTenant(string $id): Tenant
    {
        if ($this->tenant !== null && $this->tenant->getId() === $id) {
            return $this->tenant;
        }

        $tenant = $this->tenantRepository->find($id);

        if (!$tenant instanceof Tenant) {
            throw new RuntimeException(sprintf('Tenant "%s" not found.', $id));
        }

        $this->tenant      = $tenant;
        $this->initialized = true;

        return $tenant;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentTenant(): Tenant
    {
        if (!$this->initialized || $this->tenant === null) {
            throw new RuntimeException('Tenant context not initialized.');
        }

        return $this->tenant;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentTenant(Tenant $tenant): void
    {
        if ($this->initialized) {
            throw new LogicException('Tenant context already initialized.');
        }

        $this->tenant      = $tenant;
        $this->initialized = true;
    }

    /**
     * {@inheritDoc}
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    /**
     * {@inheritDoc}
     */
    public function clearTenant(): void
    {
        if (!$this->initialized) {
            throw new RuntimeException('Tenant context not initialized.');
        }

        $this->tenant      = null;
        $this->initialized = false;
    }

    /**
     * Initializes the tenant context from the authenticated user if possible.
     *
     * @return void
     */
    private function initializeFromSecurity(): void
    {
        $user = $this->security->getUser();

        if ($user !== null && method_exists($user, 'getTenant')) {
            $tenant = $user->getTenant();

            if ($tenant instanceof Tenant) {
                $this->tenant      = $tenant;
                $this->initialized = true;
            }
        }
    }
}
