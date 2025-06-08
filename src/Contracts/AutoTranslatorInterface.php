<?php

declare(strict_types=1);

namespace App\Contracts;

interface AutoTranslatorInterface
{
    /**
     * Translate a given text from source language to target language.
     *
     * @return string the translated text
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string;
}
