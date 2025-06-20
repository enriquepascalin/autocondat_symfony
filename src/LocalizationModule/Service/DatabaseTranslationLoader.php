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

namespace App\LocalizationModule\Service;

use App\MultitenancyModule\Entity\Tenant;
use App\MultitenancyModule\Service\TenantContext;
use App\LocalizationModule\Repository\TranslationEntryRepository;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseTranslationLoader implements LoaderInterface
{
    /**
     * DatabaseTranslationLoader constructor.
     */
    public function __construct(
        private readonly TranslationEntryRepository $translationEntryRepository,
        private readonly TenantContext $tenantContext
    ) {
    }

    /**
     * Loads translations from the database.
     *
     * @param mixed  $resource the resource to load (not used in this implementation)
     * @param string $locale   the locale for which to load translations
     * @param string $domain   the domain for which to load translations (default is 'messages')
     *
     * @return MessageCatalogue the loaded message catalogue
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        $catalogue = new MessageCatalogue($locale);

        $globalCatalogue = new MessageCatalogue($locale);
        foreach ($this->getGlobalOverrides($locale, $domain) as $entry) {
            $globalCatalogue->set($entry->getKey(), $entry->getValue(), $domain);
        }
        $catalogue->add($globalCatalogue);

        if ($tenant = $this->tenantContext->getCurrentTenant()) {
            $tenantCatalogue = new MessageCatalogue($locale);
            foreach ($this->getTenantOverrides($tenant, $locale, $domain) as $entry) {
                $tenantCatalogue->set($entry->getKey(), $entry->getValue(), $domain);
            }
            $catalogue->add($tenantCatalogue);
        }

        return $catalogue;
    }

    /**
     * Loads translations for a specific tenant.
     *
     * @param Tenant $tenant the tenant for which to load translations
     * @param string $locale the locale for which to load translations
     * @param string $domain the domain for which to load translations (default is 'messages')
     *
     * @return MessageCatalogue the loaded message catalogue with tenant-specific translations
     */
    private function getTenantOverrides(Tenant $tenant, string $locale, string $domain): array
    {
        return $this->translationEntryRepository
            ->findBy([
                'tenant' => $tenant,
                'locale' => $locale,
                'domain' => $domain,
                'isOverride' => true,
            ]);
    }

    /**
     * Retrieves global overrides for a specific locale and domain.
     *
     * @param string $locale the locale for which to retrieve overrides
     * @param string $domain the domain for which to retrieve overrides
     *
     * @return array an array of translation entries that are global overrides
     */
    private function getGlobalOverrides(string $locale, string $domain): array
    {
        return $this->translationEntryRepository
            ->findBy([
                'tenant' => null,
                'locale' => $locale,
                'domain' => $domain,
                'isOverride' => true,
            ]);
    }
}
