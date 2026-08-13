<?php 
namespace App\Services;

use App\Models\Vendor;

class TenantService
{
    public function currentVendor(): ?Vendor
    {
        return auth()->user()?->vendor;
    }
}
