<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DeliveryStatusEnum: int implements TranslatableInterface
{
    case QUEUED = 0;    // Queued
    case SENT = 1;      // Sent
    case DELIVERED = 2; // Delivered
    case FAILED = 3;    // Failed

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('delivery_status.'.$this->name, [], 'enums', $locale);
    }
}
