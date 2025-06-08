<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AckActionEnum: int implements TranslatableInterface
{
    case DISMISSED = 0; // Temporarily closed.
    case CONFIRMED = 1; // User saw the notification.
    case AGREED = 2; // Accepted terms (e.g., legal docs).

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('ack_action.'.$this->name, [], 'enums', $locale);
    }
}
