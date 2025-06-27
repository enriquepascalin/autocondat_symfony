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

namespace App\NotificationModule\Service;

use App\NotificationModule\Entity\Audience;
use App\NotificationModule\ValueObject\Recipient;
use App\CRMModule\Repository\ContactRepository;
use App\NotificationModule\Exception\InvalidAudienceConfigurationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service for handling audience resolution and management.
 * 
 * This service resolves audience entities to actual recipient lists,
 * handling both static lists and dynamic segments from integrated systems.
 */
final class AudienceService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_PREFIX = 'audience_recipients_';

    private ValidatorInterface $validator;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @param ContactRepository $contactRepository CRM contact repository
     * @param CacheItemPoolInterface $cache Cache pool for storing resolved recipients
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContactRepository $contactRepository,
        private readonly CacheItemPoolInterface $cache
    ) {
        $this->validator = Validation::createValidator();
    }

    /**
     * Resolves an audience to a list of recipients.
     *
     * @param Audience $audience The audience to resolve
     * @return Recipient[] Array of resolved recipients
     * @throws InvalidAudienceConfigurationException If audience configuration is invalid
     */
    public function resolveAudience(Audience $audience): array
    {
        $cacheKey = self::CACHE_PREFIX . $audience->getId();
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $recipients = match ($audience->getType()) {
            'static_list' => $this->resolveStaticList($audience),
            'crm_segment' => $this->resolveCrmSegment($audience),
            'user_query' => $this->resolveUserQuery($audience),
            default => throw new InvalidAudienceConfigurationException(
                sprintf('Unsupported audience type: %s', $audience->getType())
            ),
        };

        $cacheItem->set($recipients);
        $cacheItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cacheItem);

        return $recipients;
    }

    /**
     * Resolves a static list audience.
     *
     * @param Audience $audience The audience to resolve
     * @return Recipient[] Array of recipients
     * 
     * @throws InvalidAudienceConfigurationException If configuration is invalid
     */
    private function resolveStaticList(Audience $audience): array
    {
        $config = $audience->getConfiguration();
        
        if (!$this->validateStaticListConfig($config)) {
            throw new InvalidAudienceConfigurationException(
                'Invalid configuration for static list audience'
            );
        }

        $recipients = [];
        foreach ($config['recipients'] as $recipientData) {
            $recipients[] = new Recipient(
                $recipientData['identifier'],
                $recipientData['type'],
                $recipientData['name'] ?? null
            );
        }

        return $recipients;
    }

    /**
     * Resolves a CRM segment audience.
     *
     * @param Audience $audience The audience to resolve
     * @return Recipient[] Array of recipients
     * 
     * @throws InvalidAudienceConfigurationException If configuration is invalid
     */
    private function resolveCrmSegment(Audience $audience): array
    {
        $config = $audience->getConfiguration();
        
        if (!$this->validateCrmSegmentConfig($config)) {
            throw new InvalidAudienceConfigurationException(
                'Invalid configuration for CRM segment audience'
            );
        }

        $contacts = $this->contactRepository->findBySegmentId($config['segment_id']);
        $recipients = [];

        foreach ($contacts as $contact) {
            $recipients[] = new Recipient(
                $contact->getEmail(),
                'email',
                $contact->getFullName()
            );
        }

        return $recipients;
    }

    /**
     * Resolves a user query audience.
     *
     * @param Audience $audience The audience to resolve
     * @return Recipient[] Array of recipients
     * 
     * @throws InvalidAudienceConfigurationException If configuration is invalid
     */
    private function resolveUserQuery(Audience $audience): array
    {
        $config = $audience->getConfiguration();
        
        if (!$this->validateUserQueryConfig($config)) {
            throw new InvalidAudienceConfigurationException(
                'Invalid configuration for user query audience'
            );
        }

        // Execute custom DQL query
        $query = $this->entityManager->createQuery($config['query']);
        $results = $query->getResult();
        $recipients = [];

        foreach ($results as $user) {
            $recipients[] = new Recipient(
                $user->getEmail(),
                'email',
                $user->getFullName()
            );
        }

        return $recipients;
    }

    /**
     * Validates audience configuration.
     *
     * @param array $configuration The audience configuration
     * @param string $type The audience type
     * @return bool True if valid, false otherwise
     */
    public function validateConfiguration(array $configuration, string $type): bool
    {
        return match ($type) {
            'static_list' => $this->validateStaticListConfig($configuration),
            'crm_segment' => $this->validateCrmSegmentConfig($configuration),
            'user_query' => $this->validateUserQueryConfig($configuration),
            default => false,
        };
    }

    /**
     * Validates configuration for static list audience.
     *
     * @param array $config Configuration array
     * @return bool True if valid, false otherwise
     */
    private function validateStaticListConfig(array $config): bool
    {
        $constraints = new Assert\Collection([
            'recipients' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'identifier' => [
                            new Assert\NotBlank(),
                            new Assert\Type('string'),
                            new Assert\Length(['min' => 3, 'max' => 255])
                        ],
                        'type' => [
                            new Assert\NotBlank(),
                            new Assert\Choice(['email', 'sms', 'push'])
                        ],
                        'name' => [
                            new Assert\Optional([
                                new Assert\Type('string'),
                                new Assert\Length(['max' => 255])
                            ])
                        ]
                    ])
                ])
            ])
        ]);

        $violations = $this->validator->validate($config, $constraints);
        return count($violations) === 0;
    }

    /**
     * Validates configuration for CRM segment audience.
     *
     * @param array $config Configuration array
     * @return bool True if valid, false otherwise
     */
    private function validateCrmSegmentConfig(array $config): bool
    {
        $constraints = new Assert\Collection([
            'segment_id' => [
                new Assert\NotBlank(),
                new Assert\Type('integer'),
                new Assert\Positive()
            ],
            'active_only' => [
                new Assert\Optional([
                    new Assert\Type('bool')
                ])
            ]
        ]);

        $violations = $this->validator->validate($config, $constraints);
        return count($violations) === 0;
    }

    /**
     * Validates configuration for user query audience.
     *
     * @param array $config Configuration array
     * @return bool True if valid, false otherwise
     */
    private function validateUserQueryConfig(array $config): bool
    {
        $constraints = new Assert\Collection([
            'query' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Regex([
                    'pattern' => '/^SELECT\s+/i',
                    'message' => 'Query must start with SELECT'
                ]),
                new Assert\Regex([
                    'pattern' => '/FROM\s+/i',
                    'message' => 'Query must contain FROM clause'
                ])
            ],
            'max_results' => [
                new Assert\Optional([
                    new Assert\Type('integer'),
                    new Assert\Positive()
                ])
            ]
        ]);

        $violations = $this->validator->validate($config, $constraints);
        return count($violations) === 0;
    }

    /**
     * Clears the cache for a specific audience.
     *
     * @param Audience $audience The audience to clear cache for
     * @return bool True if successful, false otherwise
     */
    public function clearAudienceCache(Audience $audience): bool
    {
        $cacheKey = self::CACHE_PREFIX . $audience->getId();
        return $this->cache->deleteItem($cacheKey);
    }

    /**
     * Clears cache for all audiences.
     *
     * @return bool True if successful, false otherwise
     */
    public function clearAllAudienceCache(): bool
    {
        return $this->cache->clear();
    }
}