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
}
