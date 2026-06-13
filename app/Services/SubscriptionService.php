<?php 

class SubscriptionService
{
    public function getActiveSubscription(Vendor $vendor): ?Subscription
    {
        return $vendor->activeSubscription;
    }
    public function getCurrentPlan(Vendor $vendor): ?Plan
    {
        return optional($vendor->activeSubscription)->plan;
    }
    public function canCreateUser(Vendor $vendor): bool
    {
        $plan = $this->getCurrentPlan($vendor);
        if (! $plan) {
            return false;
        }
        if ($plan->is_unlimited_users) {
            return true;
        }
        return $vendor->users()->count() < $plan->max_users;
    }
}



