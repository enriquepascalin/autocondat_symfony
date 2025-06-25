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

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Entity\NotificationTemplate;
use App\NotificationModule\Exception\TemplateRenderingException;
use App\NotificationModule\Repository\NotificationTemplateRepository;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\Error;

final class TemplateRendererService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly NotificationTemplateRepository $templateRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Renders notification content from template
     *
     * @throws TemplateRenderingException
     */
    public function render(Notification $notification): string
    {
        $template = $this->getTemplate($notification);
        
        try {
            return $this->twig->render($template->getContentPath(), [
                'notification' => $notification,
                'recipient' => $notification->getPrimaryRecipient(),
                'context' => $notification->getContext()
            ]);
        } catch (Error $e) {
            $this->logger->error('Template rendering failed', [
                'template' => $template->getId(),
                'error' => $e->getMessage()
            ]);
            
            throw new TemplateRenderingException(
                $template->getId(),
                'Template rendering error',
                $e
            );
        }
    }

    /**
     * Validates template before rendering
     */
    public function validateTemplate(Notification $notification): void
    {
        $template = $this->getTemplate($notification);
        
        if (!$template->isActive()) {
            throw new \DomainException('Template is inactive');
        }
        
        if ($template->getTenantId() !== $notification->getTenantId()) {
            throw new \DomainException('Template tenant mismatch');
        }
    }

    private function getTemplate(Notification $notification): NotificationTemplate
    {
        $template = $this->templateRepository->find($notification->getTemplateId());
        
        if (!$template) {
            throw new \DomainException(
                "Template not found: {$notification->getTemplateId()}"
            );
        }
        
        return $template;
    }
}