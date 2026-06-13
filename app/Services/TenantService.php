<?php 

class TenantService
{
    public function currentVendor(): ?Vendor
    {
        return auth()->user()?->vendor;
    }
}
