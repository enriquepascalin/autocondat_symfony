<?php
namespace App\Tests\Service\LocalizationModule;

use App\Service\LocalizationModule\GoogleTranslateService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GoogleTranslateServiceTest extends TestCase
{
    public function testTranslateReturnsTranslatedTextOnSuccess(): void
    {
        $text = 'Hello';
        $sourceLang = 'en';
        $targetLang = 'fr';
        $apiKey = 'test-api-key';
        $expectedTranslation = 'Bonjour';

        // Stubbed HTTP response with the expected JSON structure
        $responseStub = $this->createStub(ResponseInterface::class);
        $responseStub->method('toArray')->willReturn([
            'data' => [
                'translations' => [
                    ['translatedText' => $expectedTranslation]
                ]
            ]
        ]);

        // Mock HttpClient to expect a POST request with correct parameters
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
                       ->method('request')
                       ->with(
                           'POST',
                           'https://translation.googleapis.com/language/translate/v2',
                           $this->callback(function ($options) use ($text, $sourceLang, $targetLang, $apiKey) {
                               $this->assertIsArray($options);
                               $this->assertArrayHasKey('query', $options);
                               $query = $options['query'];
                               $this->assertEquals($apiKey, $query['key'] ?? null);
                               $this->assertEquals($text, $query['q'] ?? null);
                               $this->assertEquals($sourceLang, $query['source'] ?? null);
                               $this->assertEquals($targetLang, $query['target'] ?? null);
                               $this->assertEquals('text', $query['format'] ?? null);
                               return true;
                           })
                       )
                       ->willReturn($responseStub);

        $service = new GoogleTranslateService($httpClientMock, $apiKey);

        // Act
        $result = $service->translate($text, $sourceLang, $targetLang);

        // Assert
        $this->assertSame($expectedTranslation, $result, "Should return the translated text from Google API response.");
    }

    public function testTranslateReturnsOriginalTextOnMalformedResponse(): void
    {
        $text = 'Goodbye';
        $sourceLang = 'en';
        $targetLang = 'es';
        $apiKey = 'dummy-key';

        // Stub response missing the 'translatedText' field
        $responseStub = $this->createStub(ResponseInterface::class);
        $responseStub->method('toArray')->willReturn([
            'data' => [
                'translations' => [
                    ['detectedSourceLanguage' => 'en'] // no 'translatedText'
                ]
            ]
        ]);

        $httpClientStub = $this->createStub(HttpClientInterface::class);
        $httpClientStub->method('request')->willReturn($responseStub);

        $service = new GoogleTranslateService($httpClientStub, $apiKey);

        // Act
        $result = $service->translate($text, $sourceLang, $targetLang);

        // Assert
        $this->assertSame($text, $result, "Should return the original text if the API response is missing translation data.");
    }

    public function testTranslateThrowsExceptionOnRequestFailure(): void
    {
        $text = 'Test';
        $apiKey = 'key';

        $responseStub = $this->createStub(ResponseInterface::class);
        $responseStub->method('toArray')->willThrowException(new \RuntimeException("HTTP error"));

        $httpClientStub = $this->createStub(HttpClientInterface::class);
        $httpClientStub->method('request')->willReturn($responseStub);

        $service = new GoogleTranslateService($httpClientStub, $apiKey);

        // Assert that an exception is thrown when the HTTP client fails
        $this->expectException(\RuntimeException::class);
        $service->translate($text, 'en', 'de');
    }
}