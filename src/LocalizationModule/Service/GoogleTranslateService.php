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

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Contracts\AutoTranslatorInterface;

class GoogleTranslateService implements AutoTranslatorInterface
{
    /**
     * GoogleTranslateService constructor.
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
    ) {
    }

    /**
     * Translate a given text from source language to target language using Google Translate API.
     *
     * @param string $text       the text to translate
     * @param string $sourceLang The language code of the source language (e.g., 'en', 'fr').
     * @param string $targetLang The language code of the target language (e.g., 'en', 'fr').
     *
     * @return string the translated text
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
