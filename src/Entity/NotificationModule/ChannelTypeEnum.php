<?php

namespace App\Entity\NotificationModule;

enum ChannelTypeEnum: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case IN_APP = 'in_app'; // Uses Symfony Mercure or WebSocket.
    case SLACK = 'slack';
    case PUSH = 'push';     // Mobile push notifications.
}