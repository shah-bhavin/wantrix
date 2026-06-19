<?php

namespace App\Actions\Billing;

use App\Models\Plan;
use App\Models\Vendor;
use App\Models\SubscriptionChange;

class CreateSubscriptionChangeAction
{
    public function execute(Vendor $vendor, ?Plan $oldPlan, Plan $newPlan, string $reason): void
    {
        SubscriptionChange::create([
            'vendor_id' => $vendor->id,
            'old_plan_id' => $oldPlan?->id,
            'new_plan_id' => $newPlan->id,
            'reason' => $reason,
        ]);
    }
}
