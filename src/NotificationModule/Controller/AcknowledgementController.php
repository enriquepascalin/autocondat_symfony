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

namespace App\NotificationModule\Controller;

use App\NotificationModule\Entity\AckActionEnum;
use App\NotificationModule\Service\AcknowledgementService;
use App\NotificationModule\Entity\Notification;
use App\UserModule\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling notification acknowledgements.
 */
class AcknowledgementController extends AbstractController
{
    /**
     * Record a user's acknowledgement action on a notification.
     *
     * @Route("/notifications/{id}/acknowledge", 
     *        name="notification_acknowledge", 
     *        methods={"POST"}, 
     *        requirements={"id"="\d+"})
     *
     * @param Request $request HTTP request
     * @param Notification $notification The notification to acknowledge
     * @param AcknowledgementService $ackService Acknowledgement service
     * @return JsonResponse
     */
    public function acknowledge(
        Request $request,
        Notification $notification,
        AcknowledgementService $ackService
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        $action = $request->request->get('action');
        
        try {
            $ackAction = AckActionEnum::from($action);
            $acknowledgement = $ackService->recordAcknowledgement($user, $notification, $ackAction);
            
            return $this->json([
                'status' => 'success',
                'acknowledgement_id' => $acknowledgement->getId(),
                'notification_status' => $notification->getStatus()->value
            ]);
        } catch (\ValueError $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid action specified'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get acknowledgement status for a notification.
     *
     * @Route("/notifications/{id}/ack-status", 
     *        name="notification_ack_status", 
     *        methods={"GET"}, 
     *        requirements={"id"="\d+"})
     *
     * @param Notification $notification The notification to check
     * @param AcknowledgementService $ackService Acknowledgement service
     * @return JsonResponse
     */
    public function acknowledgementStatus(
        Notification $notification,
        AcknowledgementService $ackService
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        
        $acknowledgement = $ackService->findUserAcknowledgement($user, $notification);
        
        return $this->json([
            'has_acknowledged' => $acknowledgement !== null,
            'action' => $acknowledgement?->getAction()->value,
            'acknowledged_at' => $acknowledgement?->getAcknowledgedAt()?->format(\DateTimeInterface::ATOM)
        ]);
    }
}