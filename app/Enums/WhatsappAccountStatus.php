<?php

namespace App\Enums;

enum WhatsappAccountStatus: string
{
    case PENDING = 'pending';
    case CONNECTED = 'connected';
    case DISCONNECTED = 'disconnected';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::CONNECTED => 'Connected',
            self::DISCONNECTED => 'Disconnected',
            self::SUSPENDED => 'Suspended',
        };
    }
}
