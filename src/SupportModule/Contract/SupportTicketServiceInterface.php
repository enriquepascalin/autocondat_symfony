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

namespace App\SupportModule\Contract;

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Entity\Channel;
use App\MultitenancyModule\Entity\Tenant;
use App\NotificationModule\ValueObject\Recipient;

interface SupportTicketServiceInterface
{
    /**
     * Creates a support ticket for circuit breaker failures
     * 
     * This method is used to log critical issues with notification channels
     * when they become unavailable or experience significant issues.
     * It captures relevant context such as the channel,
     * tenant, recipient, and a detailed message describing the issue.
     * 
     * @param Channel                $channel   Canal afectado
     * @param Tenant                 $tenant    Tenant propietario
     * @param string                 $message   Descripción del problema
     * @param Recipient|null         $recipient Destinatario implicado
     * @param array<string,mixed>|null $options  Parámetros adicionales
     *
     * @throws \RuntimeException       Cuando la creación falla
     * @throws \InvalidArgumentException Cuando los parámetros son inválidos
     */
    public function createCircuitBreakerTicket(
        Channel $channel,
        Tenant $tenant,
        string $message,
        ?Recipient $recipient = null,
        ?array $options = []
    ): void;
}