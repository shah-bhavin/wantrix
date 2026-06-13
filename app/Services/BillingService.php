<?php

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;


class BillingService
{
    public function createInvoice(Subscription $subscription, float $amount): Invoice
    {
        return Invoice::create([
            'vendor_id' => $subscription->vendor_id,
            'subscription_id' => $subscription->id,
            'amount' => $amount,
            'status' => InvoiceStatus::PENDING,
            'issued_at' => now(),
        ]);
    }
    public function createPayment(Invoice $invoice): Payment
    {
        return Payment::create([
            'vendor_id' => $invoice->vendor_id,
            'subscription_id' => $invoice->subscription_id,
            'amount' => $invoice->amount,
            'status' => PaymentStatus::PENDING,
        ]);
    }
}
