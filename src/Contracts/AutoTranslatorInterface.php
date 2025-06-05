<?php

namespace App\Contracts;

interface AutoTranslatorInterface
{
    /**
     * Translate a given text from source language to target language.
     * 
     * @param string $text
     * @param string $sourceLang
     * @param string $targetLang
     * @return string The translated text.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string;
}