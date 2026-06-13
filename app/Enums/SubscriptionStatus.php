<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case TRIAL = 'trial';

    case ACTIVE = 'active';

    case EXPIRED = 'expired';

    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::TRIAL => 'Trial',
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
        };
    }
    public function canAccessFeatures(): bool
    {
        return in_array($this, [
            self::TRIAL,
            self::ACTIVE,
        ]);
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }
}