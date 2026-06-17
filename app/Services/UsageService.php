<?php

namespace App\Services;

use App\Models\Vendor;

class UsageService
{
    public function userCount(Vendor $vendor): int
    {
        return $vendor->users()->count();
    }

    public function contactCount(Vendor $vendor): int
    {
        return 0;
    }

    public function campaignCount(Vendor $vendor): int
    {
        return 0;
    }


}
