<?php

namespace App\Support;

use App\Models\Vendor;

class UsageLimit
{
    public static function canCreateContact(Vendor $vendor): bool
    {
        $plan = $vendor->activePlan();

        if (!$plan) {
            return false;
        }

        if ($plan->is_unlimited_contacts) {
            return true;
        }

        return $vendor->contacts()->count() < $plan->max_contacts;
    }


    public static function canCreateCampaign(Vendor $vendor): bool
    {
        $plan = $vendor->activePlan();

        if (!$plan) {
            return false;
        }

        if ($plan->is_unlimited_campaigns) {
            return true;
        }

        $count = $vendor->campaigns()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $count < $plan->max_campaigns_per_month;
    }

    public static function canCreateUser(Vendor $vendor): bool
    {
        $plan = $vendor->activePlan();

        if (!$plan) {
            return false;
        }

        if ($plan->is_unlimited_users) {
            return true;
        }

        return $vendor->users()->count() < $plan->max_users;
    }

    public static function canCreateWhatsappAccount(Vendor $vendor): bool
    {
        $plan = $vendor->activePlan();

        if (!$plan) {
            return false;
        }

        if ($plan->is_unlimited_whatsapp_numbers) {
            return true;
        }

        return $vendor->whatsappAccounts()->count() < $plan->max_whatsapp_numbers;
    }

    public static function campaignsUsedThisMonth(Vendor $vendor): int
    {
        return $vendor->campaigns()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

}
