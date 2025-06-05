<?php

namespace App\Service\LocalizationModule;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Contracts\AutoTranslatorInterface;

class GoogleTranslateService implements AutoTranslatorInterface
{
    /**
     * GoogleTranslateService constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param string $apiKey
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey
    ) {}

    /**
     * Translate a given text from source language to target language using Google Translate API.
     * 
     * @param string $text The text to translate.
     * @param string $sourceLang The language code of the source language (e.g., 'en', 'fr').
     * @param string $targetLang The language code of the target language (e.g., 'en', 'fr').
     * @return string The translated text.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $response = $this->httpClient->request('POST', 'https://translation.googleapis.com/language/translate/v2', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $text,
                'source' => $sourceLang,
                'target' => $targetLang,
                'format' => 'text',
            ],
        ]);

        return $response->toArray()['data']['translations'][0]['translatedText'] ?? $text;
    }
}
