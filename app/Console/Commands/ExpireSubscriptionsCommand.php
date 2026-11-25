<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-subscriptions-command')]
#[Description('Command description')]
class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'app:expire-subscriptions-command';

    protected $description = 'Expire subscriptions whose end date has passed';

    public function handle(): int
    {
        $expiredCount = Subscription::query()
            ->whereIn('status', [
                SubscriptionStatus::TRIAL,
                SubscriptionStatus::ACTIVE,
            ])
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->update([
                'status' => SubscriptionStatus::EXPIRED,
                'updated_at' => now(),
            ]);

        $this->info("Expired subscriptions: {$expiredCount}");

        return self::SUCCESS;
    }
}
