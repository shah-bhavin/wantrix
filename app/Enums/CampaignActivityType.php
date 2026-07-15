<?php

namespace App\Enums;

enum CampaignActivityType: string
{
    case CREATED = 'created';
    case MESSAGES_GENERATED = 'messages_generated';
    case STARTED = 'started';
    case PAUSED = 'paused';
    case RESUMED = 'resumed';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Campaign Created',
            self::MESSAGES_GENERATED => 'Messages Generated',
            self::STARTED => 'Campaign Started',
            self::PAUSED => 'Campaign Paused',
            self::RESUMED => 'Campaign Resumed',
            self::COMPLETED => 'Campaign Completed',
            self::CANCELLED => 'Campaign Cancelled',
        };
    }
}