<?php

namespace App\Service\Translation;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Service for translating enumeration values.
 */
final class EnumTranslator
{
    /**
     * @param TranslatorInterface $translator Symfony translator service
     */
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * Translates the given enumeration value.
     *
     * @param UnitEnum $enumerationValue The enumeration value to translate
     * @param string|null $locale Optional locale override
     * @return string The translated string
     */
    public function translateEnumeration(UnitEnum $enumerationValue, ?string $locale = null): string
    {
        $key = sprintf('enum.%s.%s', $enum::class, $enum->name);
        return $this->translator->trans($key, [], 'enums', $locale);
    }
}