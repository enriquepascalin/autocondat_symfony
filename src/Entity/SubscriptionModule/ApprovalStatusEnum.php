<?php

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ApprovalStatusEnum: int implements TranslatableInterface
{
    case PENDING = 0;   // Pending
    case APPROVED = 1;  // Approved
    case REJECTED = 2;  // Rejected
    case CANCELLED = 3; // Cancelled

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('approval_status.' . $this->name, [], 'enums', $locale);
    }
}