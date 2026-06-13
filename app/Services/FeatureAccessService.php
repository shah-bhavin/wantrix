<?php

use App\Models\Plan;
use App\Models\Vendor;

class FeatureAccessService
{
    public function currentPlan(Vendor $vendor): ?Plan
    {
        return optional($vendor->activeSubscription)->plan;
    }
    public function canCreateUser(Vendor $vendor): bool
    {
        $plan = $this->currentPlan($vendor);

        if (!$plan) {
            return false;
        }

        if (!$vendor->activeSubscription) {
            return false;
        }

        if (!$vendor->activeSubscription->status->canAccessFeatures()) {
            return false;
        }

        if ($plan->is_unlimited_users) {
            return true;
        }

        return $vendor->users()->count() < $plan->max_users;
    }
}
