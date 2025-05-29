<?php

namespace App\Entity\SupportModule;

enum TicketStatusEnum: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case REOPENED = 'reopened';
    case ESCALATED = 'escalated';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';
    case PENDING = 'pending';
    case WAITING_FOR_CUSTOMER = 'waiting_for_customer';
    case WAITING_FOR_SUPPORT = 'waiting_for_support';
    case AWAITING_RESPONSE = 'awaiting_response';
    case UNDER_REVIEW = 'under_review';
    case IN_REVIEW = 'in_review';
}