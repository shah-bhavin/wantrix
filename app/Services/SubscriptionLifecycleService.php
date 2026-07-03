<?php 
namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Vendor;

class SubscriptionLifecycleService
{
    public function activate(Subscription $subscription): void 
    {
        $subscription->update([
            'status' => SubscriptionStatus::ACTIVE,
        ]);
    }

    public function expire(Subscription $subscription): void
    {
        $subscription->update([
            'status' => SubscriptionStatus::EXPIRED,
        ]);
    }

    public function cancel(Subscription $subscription): void
    {
        $subscription->update([
            'status' => SubscriptionStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);
    }

    public function renew(Subscription $subscription, int $days): void
    {
        $subscription->update([
            'status' => SubscriptionStatus::ACTIVE,
            'ends_at' => now()->addDays($days),
        ]);
    }

    public function upgradePlan(Vendor $vendor, Plan $newPlan): Subscription
    {
        $current = $vendor->activeSubscription;

        if ($current) {
            $current->update([
                'status' => SubscriptionStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);
        }

        return Subscription::create([
            'vendor_id' => $vendor->id,
            'plan_id' => $newPlan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'starts_at' => now(),
            'created_by' => auth()->id(),
        ]);
    }





}