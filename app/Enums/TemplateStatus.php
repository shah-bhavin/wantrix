<?php

namespace App\Enums;

enum TemplateStatus:string
{
    case APPROVED = 'APPROVED';

    case PENDING = 'PENDING';

    case REJECTED = 'REJECTED';

    case PAUSED = 'PAUSED';

    case DISABLED = 'DISABLED';

    case IN_APPEAL = 'IN_APPEAL';
}