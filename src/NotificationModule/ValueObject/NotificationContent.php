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

namespace App\NotificationModule\ValueObject;

use App\NotificationModule\Exception\InvalidContentException;

/**
 * Represents structured notification content with strict validation.
 */
final class NotificationContent
{
    private string $subject;
    private string $textContent;
    private string $htmlContent;
    private array $attachments;
    private array $metadata;

    /**
     * @param string $subject Notification subject (min 3 chars, max 255)
     * @param string $textContent Plain text content (min 10 chars)
     * @param string $htmlContent HTML formatted content
     * @param array<array{name: string, content: string, type: string}> $attachments File attachments
     * @param array $metadata Additional content metadata
     * 
     * @throws InvalidContentException If validation fails
     */
    public function __construct(
        string $subject,
        string $textContent,
        string $htmlContent = '',
        array $attachments = [],
        array $metadata = []
    ) {
        $this->subject = $subject;
        $this->textContent = $textContent;
        $this->htmlContent = $htmlContent;
        $this->attachments = $attachments;
        $this->metadata = $metadata;
        
        $this->validate();
    }

    /**
     * Validates content integrity with strict rules.
     * 
     * @throws InvalidContentException
     */
    private function validate(): void
    {
        // Subject validation
        $subjectLength = mb_strlen(trim($this->subject));
        if ($subjectLength < 3) {
            throw new InvalidContentException(
                'Subject must be at least 3 characters long'
            );
        }
        
        if ($subjectLength > 255) {
            throw new InvalidContentException(
                'Subject cannot exceed 255 characters'
            );
        }

        // Text content validation
        $textLength = mb_strlen(trim($this->textContent));
        if ($textLength < 10) {
            throw new InvalidContentException(
                'Text content must be at least 10 characters long'
            );
        }

        // HTML content validation (if provided)
        if ($this->htmlContent !== '' && mb_strlen(trim($this->htmlContent)) < 10) {
            throw new InvalidContentException(
                'HTML content must be at least 10 characters long if provided'
            );
        }

        // Attachments validation
        foreach ($this->attachments as $index => $attachment) {
            if (!isset($attachment['name'], $attachment['content'], $attachment['type'])) {
                throw new InvalidContentException(
                    "Attachment at index $index requires name, content, and type"
                );
            }

            if (!is_string($attachment['name']) || trim($attachment['name']) === '') {
                throw new InvalidContentException(
                    "Attachment name at index $index must be a non-empty string"
                );
            }

            if (!is_string($attachment['content'])) {
                throw new InvalidContentException(
                    "Attachment content at index $index must be a string"
                );
            }

            if (!is_string($attachment['type']) || trim($attachment['type']) === '') {
                throw new InvalidContentException(
                    "Attachment type at index $index must be a non-empty string"
                );
            }
        }
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTextContent(): string
    {
        return $this->textContent;
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getMetadataValue(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Renders content for a specific channel.
     */
    public function forChannel(string $channel): array
    {
        return match ($channel) {
            'email' => [
                'subject' => $this->subject,
                'text' => $this->textContent,
                'html' => $this->htmlContent,
                'attachments' => $this->attachments
            ],
            'sms' => ['body' => $this->textContent],
            'push' => [
                'title' => $this->subject,
                'body' => $this->textContent
            ],
            'webhook' => [
                'subject' => $this->subject,
                'content' => $this->textContent,
                'metadata' => $this->metadata
            ],
            default => throw new \InvalidArgumentException(
                "Unsupported channel: $channel"
            )
        };
    }
}