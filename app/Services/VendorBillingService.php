<?php

namespace App\Services;

use App\Models\User;

class VendorBillingService
{
    public function getData(User $user): array
    {
        $vendor = $user->vendor;
        $subscription = $vendor?->activeSubscription;
        //$subscription = $vendor?->currentSubscription;
        $plan = $subscription?->plan;

        return [
            'plan_name' => $plan?->name,
            'status' => $subscription?->status?->value,
            'starts_at' => $subscription?->starts_at,
            'ends_at' => $subscription?->ends_at,
            'days_left' => $subscription ? max(0, now()->diffInDays($subscription->ends_at, false)) : 0,
        ];
    }
}
