<?php

namespace App\Entity\ProjectModule;

enum ProjectTypeEnum: string
{
    case SCRUM = 'scrum';
    case KANBAN = 'kanban';
    case WATERFALL = 'waterfall';
    case CUSTOM = 'custom';
}