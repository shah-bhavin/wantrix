<?php

namespace App\Enums;

enum SubscriptionChangeType: string
{
    case UPGRADE = 'upgrade';

    case DOWNGRADE = 'downgrade';

    case RENEWAL = 'renewal';

    case CANCELLATION = 'cancellation';

    case TRIAL_STARTED = 'trial_started';

    public function label(): string
    {
        return match ($this) {

            self::UPGRADE => 'Upgrade',

            self::DOWNGRADE => 'Downgrade',

            self::RENEWAL => 'Renewal',

            self::CANCELLATION => 'Cancellation',

            self::TRIAL_STARTED => 'Trial Started',
        };
    }
}