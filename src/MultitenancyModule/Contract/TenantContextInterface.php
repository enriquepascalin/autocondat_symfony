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

interface TenantContextInterface
{
    /**
     * Retrieves the current tenant context.
     * 
     * @param string $id The tenant ID to load 
     * @throws \RuntimeException If no tenant is set
     * @return Tenant The current tenant context 
     */
    public function loadTenant(string $id): Tenant;

    /**
     * Sets the current tenant context.
     * 
     * @param Tenant $tenant The tenant to set as current
     * @throws \RuntimeException If the tenant is invalid or not found
     * @return void
     */
    public function setCurrentTenant(Tenant $tenant): void;
    
    /** 
     * Clears the current tenant context.
     * 
     * @throws \RuntimeException If no tenant is currently set
     * @return void
     */
    public function clearTenant(): void;
}