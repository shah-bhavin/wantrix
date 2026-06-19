<?php

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\Payment;

class CreatePaymentAction
{
    public function execute(Invoice $invoice): Payment
    {
        return Payment::create([
            'vendor_id' => $invoice->vendor_id,
            'subscription_id' => $invoice->subscription_id,
            'amount' => $invoice->amount,
            'currency' => $invoice->currency,
            'gateway' => 'razorpay',
            'status' => 'pending',
        ]);
    }
}
