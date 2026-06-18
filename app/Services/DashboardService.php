<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function getData(User $user): array
    {
        $vendor = $user->vendor;
        $subscription = $vendor?->activeSubscription;
        $plan = $subscription?->plan;

        return [
            'plan_name' => $plan?->name ?? 'No Plan',
            'max_users' => $plan?->max_users ?? 0,
            'current_users' => $vendor?->users()->count() ?? 0,
            'contacts_count' => 0,
            'max_contacts' => $plan?->max_contacts ?? 0,
            'subscription_days_left' => $subscription ? now()->diffInDays($subscription->ends_at, false) : 0,
        ];
    }
}
