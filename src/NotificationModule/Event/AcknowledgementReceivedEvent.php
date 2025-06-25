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

namespace App\NotificationModule\Event;

use App\NotificationModule\Entity\Acknowledgement;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event dispatched when an acknowledgement is received for a notification.
 * 
 * This event is triggered after a user interacts with a notification (e.g., marks as read, dismisses, or snoozes).
 * It carries the acknowledgement entity containing details about the user action.
 */
final class AcknowledgementReceivedEvent extends Event
{
    public const NAME = 'notification.acknowledgement_received';

    /**
     * @param Acknowledgement $acknowledgement The acknowledgement entity
     */
    public function __construct(
        private readonly Acknowledgement $acknowledgement
    ) {}

    /**
     * Get the acknowledgement entity.
     *
     * @return Acknowledgement
     */
    public function getAcknowledgement(): Acknowledgement
    {
        return $this->acknowledgement;
    }
}