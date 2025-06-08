<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum BundleStatusEnum: int implements TranslatableInterface
{
    case DRAFT = 0;    // Draft
    case ACTIVE = 1;   // Active
    case ARCHIVED = 2; // Archived
    case DELETED = 3;  // Deleted

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('bundle_status.'.$this->name, [], 'enums', $locale);
    }
}
