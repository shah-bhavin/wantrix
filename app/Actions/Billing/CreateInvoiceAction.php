<?php

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Vendor;

class CreateInvoiceAction
{
    public function execute(Vendor $vendor, Subscription $subscription, Plan $plan): Invoice
    {
        return Invoice::create([
            'vendor_id' => $vendor->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-' . now()->format('YmdHis'),
            'amount' => $plan->monthly_price,
            'currency' => 'INR',
            'status' => 'pending',
            'issued_at' => now(),
        ]);
    }
}
