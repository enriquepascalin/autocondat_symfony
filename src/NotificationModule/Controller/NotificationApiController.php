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

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Event\NotificationRequestedEvent;
use App\NotificationModule\Repository\NotificationRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/notifications')]
final class NotificationApiController
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly NotificationRepository $repository
    ) {
    }

    #[Route('', name: 'notification_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var Notification $notification */
        $notification = $this->serializer->deserialize(
            $request->getContent(),
            Notification::class,
            'json'
        );

        $errors = $this->validator->validate($notification);
        if (count($errors) > 0) {
            return new JsonResponse(
                ['errors' => (string) $errors],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->repository->save($notification, true);
        
        $this->dispatcher->dispatch(new NotificationRequestedEvent($notification));

        return new JsonResponse(
            ['id' => $notification->getId()],
            Response::HTTP_CREATED
        );
    }
}