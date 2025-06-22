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

namespace App\WorkflowModule\Contract;

interface WorkflowSubjectInterface
{
    /**
     * Get the current place in the workflow.
     * 
     * @return string The current place identifier.
     */
    public function getCurrentPlace(): string;

    /**
     * Set the current place in the workflow.
     * 
     * @param string $currentPlace The identifier of the current place.
     * @param array $context Additional context for the transition, if needed.  
     * 
     * @return void
     */
    public function setCurrentPlace(string $currentPlace, array $context = []): void;
}