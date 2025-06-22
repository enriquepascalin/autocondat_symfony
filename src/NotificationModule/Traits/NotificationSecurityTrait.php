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

namespace App\NotificationModule\Traits;

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Entity\NotificationTemplate;
use App\MultitenancyModule\Service\TenantContext;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait NotificationSecurityTrait
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TenantContext $tenantContext;

    /**
     * @required
     */
    public function setNotificationSecurityDependencies(
        AuthorizationCheckerInterface $authorizationChecker,
        TenantContext $tenantContext
    ): void {
        $this->authorizationChecker = $authorizationChecker;
        $this->tenantContext = $tenantContext;
    }

    /**
     * Validates tenant context for notification operations
     *
     * @throws AccessDeniedException
     */
    public function validateTenantContext(string $tenantId): void
    {
        $currentTenant = $this->tenantContext->getCurrentTenant();
        
        if ($currentTenant->getId() !== $tenantId) {
            throw new AccessDeniedException('Cross-tenant notification access forbidden');
        }
    }

    /**
     * Checks permission for notification creation
     *
     * @throws AccessDeniedException
     */
    public function checkCreatePermission(string $channelType): void
    {
        $permission = match($channelType) {
            'EMAIL' => 'NOTIFICATION_CREATE_EMAIL',
            'SMS' => 'NOTIFICATION_CREATE_SMS',
            'PUSH' => 'NOTIFICATION_CREATE_PUSH',
            'IN_APP' => 'NOTIFICATION_CREATE_IN_APP',
            default => 'NOTIFICATION_CREATE'
        };

        if (!$this->authorizationChecker->isGranted($permission)) {
            throw new AccessDeniedException(sprintf(
                'Missing "%s" permission for notification creation',
                $permission
            ));
        }
    }

    /**
     * Checks permission for notification template management
     *
     * @throws AccessDeniedException
     */
    public function checkTemplateManagementPermission(NotificationTemplate $template): void
    {
        $this->validateTenantContext($template->getTenantId());
        
        if (!$this->authorizationChecker->isGranted('NOTIFICATION_TEMPLATE_MANAGE', $template)) {
            throw new AccessDeniedException('Template management permission denied');
        }
    }

    /**
     * Checks permission for notification acknowledgement
     *
     * @throws AccessDeniedException
     */
    public function checkAcknowledgementPermission(Notification $notification): void
    {
        $this->validateTenantContext($notification->getTenantId());
        
        if (!$this->authorizationChecker->isGranted('NOTIFICATION_ACKNOWLEDGE', $notification)) {
            throw new AccessDeniedException('Acknowledgement permission denied');
        }
    }

    /**
     * Checks permission for notification deletion
     *
     * @throws AccessDeniedException
     */
    public function checkDeletePermission(Notification $notification): void
    {
        if ($notification->getStatus() !== 'DRAFT') {
            throw new AccessDeniedException('Only draft notifications can be deleted');
        }
        
        $this->validateTenantContext($notification->getTenantId());
        
        if (!$this->authorizationChecker->isGranted('NOTIFICATION_DELETE', $notification)) {
            throw new AccessDeniedException('Delete permission denied');
        }
    }

    /**
     * Checks permission for delivery report access
     *
     * @throws AccessDeniedException
     */
    public function checkReportAccessPermission(string $tenantId): void
    {
        $this->validateTenantContext($tenantId);
        
        if (!$this->authorizationChecker->isGranted('NOTIFICATION_REPORT_VIEW')) {
            throw new AccessDeniedException('Report access permission denied');
        }
    }
}