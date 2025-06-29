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

namespace App\SupportModule\Service;

use App\SupportModule\Contract\SupportTicketServiceInterface;
use App\NotificationModule\Entity\Channel;
use App\MultitenancyModule\Entity\Tenant;
use App\NotificationModule\ValueObject\Recipient;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Default implementation that crea­­tes, persiste y/o envía tickets de soporte
 * relacionados con fallos de circuit-breaker en canales de notificación.
 */
final class SupportTicketService implements SupportTicketServiceInterface
{
    public function __construct(
        private readonly LoggerInterface       $logger,
        private readonly HttpClientInterface   $httpClient
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function createCircuitBreakerTicket(
        Channel        $channel,
        Tenant         $tenant,
        string         $message,
        ?Recipient     $recipient = null,
        ?array         $options   = []
    ): void {
        $ticketId = Uuid::v4()->toRfc4122();

        $payload = [
            'id'        => $ticketId,
            'channel'   => $channel->value,
            'tenant'    => $tenant->getId(),
            'recipient' => $recipient?->getValue(),
            'message'   => $message,
            'options'   => $options,
            'occurredAt'=> (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
        ];

        // Registro local
        $this->logger->critical('Support ticket created', $payload);

        // Envío opcional a sistema externo
        if (isset($options['endpoint'])) {
            $this->httpClient->request('POST', $options['endpoint'], ['json' => $payload]);
        }
    }
}