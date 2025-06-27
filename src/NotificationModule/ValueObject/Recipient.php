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

use App\NotificationModule\Exception\InvalidRecipientException;

/**
 * Represents a notification recipient with validation.
 */
final class Recipient
{
    private string $identifier;
    private string $type;
    private ?string $name;
    private array $metadata;

    /**
     * @param string $identifier Unique identifier (email, phone number, push token)
     * @param string $type Channel type (email, sms, push, webhook)
     * @param string|null $name Optional display name
     * @param array $metadata Additional channel-specific parameters
     * 
     * @throws InvalidRecipientException If invalid recipient data is provided
     */
    public function __construct(
        string $identifier,
        string $type,
        ?string $name = null,
        array $metadata = []
    ) {
        $this->identifier = $identifier;
        $this->type = $type;
        $this->name = $name;
        $this->metadata = $metadata;
        
        $this->validate();
    }

    /**
     * Validates recipient based on type.
     * 
     * @throws InvalidRecipientException
     */
    private function validate(): void
    {
        match ($this->type) {
            'email' => $this->validateEmail(),
            'sms' => $this->validatePhone(),
            'push' => $this->validatePushToken(),
            'webhook' => $this->validateWebhook(),
            default => throw new InvalidRecipientException(
                "Invalid recipient type: {$this->type}"
            ),
        };
    }

    private function validateEmail(): void
    {
        if (!filter_var($this->identifier, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidRecipientException(
                "Invalid email address: {$this->identifier}"
            );
        }
    }

    private function validatePhone(): void
    {
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $this->identifier)) {
            throw new InvalidRecipientException(
                "Invalid E.164 phone format: {$this->identifier}"
            );
        }
    }

    private function validatePushToken(): void
    {
        if (strlen($this->identifier) < 64 || strlen($this->identifier) > 255) {
            throw new InvalidRecipientException(
                "Invalid push token length (64-255 chars required)"
            );
        }
    }

    private function validateWebhook(): void
    {
        if (!filter_var($this->identifier, FILTER_VALIDATE_URL)) {
            throw new InvalidRecipientException(
                "Invalid webhook URL: {$this->identifier}"
            );
        }
        
        if (!in_array(parse_url($this->identifier, PHP_URL_SCHEME), ['http', 'https'])) {
            throw new InvalidRecipientException(
                "Webhook must use HTTP/HTTPS protocol"
            );
        }
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getMetadataValue(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    public function __toString(): string
    {
        return $this->name 
            ? "{$this->name} <{$this->identifier}>" 
            : $this->identifier;
    }
}