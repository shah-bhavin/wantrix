<?php

namespace App\Services;
use App\Support\UsageLimit;
use App\Models\User;

class DashboardService
{
    public function getData(User $user): array
    {
        $vendor = $user->vendor;
        $subscription = $vendor?->activeSubscription;
        $plan = $subscription?->plan;
        //echo '<pre>';print_r($plan);echo '</pre>';

        return [
            'plan_name' => $plan?->name ?? 'No Plan',
            'max_users' => $plan?->max_users ?? 0,
            'current_users' => $vendor?->users()->count() ?? 0,
            'contacts_count' => $vendor?->contacts()->count() ?? 0,
            'max_contacts' => $plan?->max_contacts ?? 0,
            'subscription_days_left' => $subscription ? now()->diffInDays($subscription->ends_at, false) : 0,
            'current_whatsapp_numbers' =>$vendor?->whatsappAccounts()->count() ?? 0,
            'max_whatsapp_numbers' => $plan?->max_whatsapp_numbers ?? 0,
            'campaigns_used_this_month' => $vendor ? UsageLimit::campaignsUsedThisMonth($vendor): 0,
            'max_campaigns_per_month' => $plan?->max_campaigns_per_month ?? 0,
        ];
    }
}
