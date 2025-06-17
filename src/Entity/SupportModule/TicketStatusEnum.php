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

namespace App\Entity\SupportModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TicketStatusEnum: int implements TranslatableInterface
{
    case OPEN = 0;                  // Open
    case IN_PROGRESS = 1;           // In progress
    case RESOLVED = 2;              // Resolved
    case CLOSED = 3;                // Closed
    case REOPENED = 4;              // Reopened
    case ESCALATED = 5;             // Escalated
    case ON_HOLD = 6;               // On hold
    case CANCELLED = 7;             // Cancelled
    case PENDING = 8;               // Pending
    case WAITING_FOR_CUSTOMER = 9;  // Waiting for customer
    case WAITING_FOR_SUPPORT = 10;  // Waiting for support
    case AWAITING_RESPONSE = 11;    // Awaiting response
    case UNDER_REVIEW = 12;         // Under review
    case IN_REVIEW = 13;            // In review

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('ticket_status.'.$this->name, [], 'enums', $locale);
    }
}
