<?php

namespace App\Actions\Onboarding;

use App\Models\Vendor;
use App\Models\Plan;
use App\Models\Subscription;

class AssignTrialSubscriptionAction
{
    public function execute(Vendor $vendor): Subscription
    {
        $plan = Plan::where('name', 'Trial')->firstOrFail();

        return Subscription::create([
            'vendor_id' => $vendor->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
        ]);
    }
}
