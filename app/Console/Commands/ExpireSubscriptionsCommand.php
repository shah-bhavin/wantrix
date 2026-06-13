<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-subscriptions-command')]
#[Description('Command description')]
class ExpireSubscriptionsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Subscription::query()
            ->whereIn('status', [
                'trial',
                'active',
            ])
            ->where('ends_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);
    }
}
