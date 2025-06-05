<?php

namespace App\Contracts;

interface AutoTranslatorInterface
{
    /**
     * Translate a given text from source language to target language.
     * 
     * @param string $text The text to translate.
     * @param string $sourceLang The language code of the source language (e.g., 'en', 'fr').
     * @param string $targetLang The language code of the target language (e.g., 'en', 'fr').
     * @return string The translated text.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string;
}