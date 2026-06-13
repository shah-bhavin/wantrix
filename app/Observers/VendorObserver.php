<?php

namespace App\Observers;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Vendor;

class VendorObserver
{
    /**
     * Handle the Vendor "created" event.
     */
    public function created(Vendor $vendor): void
    {
        $plan = Plan::defaultPlan();

        if (! $plan) {
            return;
        }

        Subscription::create([

            'vendor_id' => $vendor->id,

            'plan_id' => $plan->id,

            'status' => SubscriptionStatus::TRIAL,

            'starts_at' => now(),

            'trial_ends_at' => now()
                ->addDays($plan->trial_days),

            'created_by' => auth()->id() ?? $vendor->id, // Fallback to the vendor's own ID or a system ID

        ]);
    }

    /**
     * Handle the Vendor "updated" event.
     */
    public function updated(Vendor $vendor): void
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     */
    public function deleted(Vendor $vendor): void
    {
        //
    }

    /**
     * Handle the Vendor "restored" event.
     */
    public function restored(Vendor $vendor): void
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     */
    public function forceDeleted(Vendor $vendor): void
    {
        //
    }
}
