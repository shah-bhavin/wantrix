<?php

namespace App\Enums;

enum ContactStatus: string
{
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
    case UNSUBSCRIBED = 'unsubscribed';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::BLOCKED => 'Blocked',
            self::UNSUBSCRIBED => 'Unsubscribed',
        };
    }
}
