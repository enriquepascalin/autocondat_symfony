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

namespace App\MultitenancyModule\Contract;

use App\MultitenancyModule\Entity\Tenant;

/**
 * Defines operations for managing the tenant context during a request lifecycle.
 */
interface TenantContextInterface
{
    /**
     * Loads the tenant identified by its ID and sets it as the current context.
     *
     * @param string $id Tenant ID
     *
     * @throws \RuntimeException If the tenant is not found
     *
     * @return Tenant Loaded tenant
     */
    public function loadTenant(string $id): Tenant;

    /**
     * Returns the current tenant.
     *
     * @throws \RuntimeException If the context is not initialized
     *
     * @return Tenant Current tenant
     */
    public function getCurrentTenant(): Tenant;

    /**
     * Sets the current tenant explicitly.
     *
     * @param Tenant $tenant Tenant to set
     *
     * @throws \LogicException If the context is already initialized
     *
     * @return void
     */
    public function setCurrentTenant(Tenant $tenant): void;

    /**
     * Indicates whether the context has been initialized.
     *
     * @return bool True if initialized, false otherwise
     */
    public function isInitialized(): bool;

    /**
     * Clears the current tenant context.
     *
     * @throws \RuntimeException If no context is initialized
     *
     * @return void
     */
    public function clearTenant(): void;
}
