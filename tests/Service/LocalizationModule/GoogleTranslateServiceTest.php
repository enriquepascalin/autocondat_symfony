<?php

namespace App\Tests\Service\LocalizationModule;

use App\Service\LocalizationModule\GoogleTranslateService;
use PHPUnit\Framework\TestCase;

class GoogleTranslateServiceTest extends TestCase
{
    public function testTranslateReturnsExpectedValue()
    {
        $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY');
        $this->assertNotEmpty($apiKey);

        $service = new GoogleTranslateService($apiKey);
        $translated = $service->translate('Hello', 'es');

        $this->assertNotEquals('Hello', $translated);
        $this->assertIsString($translated);
    }
}