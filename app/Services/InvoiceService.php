<?php

namespace App\Services;

use App\Models\Vendor;

class InvoiceService
{
    public function latest(Vendor $vendor, int $limit = 10)
    {
        return $vendor->invoices()->latest()->limit($limit)->get();
    }
}
