<?php

namespace App\Service\LocalizationModule;

use App\Repository\LocalizationModule\TranslationEntryRepository;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseTranslationLoader implements LoaderInterface
{
    /**
     * DatabaseTranslationLoader constructor.
     *
     * @param TranslationEntryRepository $repository
     */
    public function __construct(
        private TranslationEntryRepository $repository
    ) {}

    /**
     * Loads translations from the database.
     *
     * @param mixed $resource The resource to load (not used in this implementation).
     * @param string $locale The locale for which to load translations.
     * @param string $domain The domain for which to load translations (default is 'messages').
     * @return MessageCatalogue The loaded message catalogue.
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        $catalogue = new MessageCatalogue($locale);
        $entries = $this->repository->findBy(['locale' => $locale, 'domain' => $domain]);

        foreach ($entries as $entry) {
            $catalogue->set($entry->getKey(), $entry->getValue(), $domain);
        }

        return $catalogue;
    }
}
