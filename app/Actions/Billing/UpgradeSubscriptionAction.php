<?php

namespace App\Actions\Billing;

use App\Models\Plan;
use App\Models\Vendor;

class UpgradeSubscriptionAction
{
    public function execute(Vendor $vendor, Plan $plan): array
    {
        $subscription = $vendor->subscriptions()->where('status', 'active')->latest()->first();

        if (!$subscription) {
            throw new \Exception('No active subscription found.');
        }

        $invoice = app(CreateInvoiceAction::class)->execute($vendor, $subscription, $plan);
        $payment = app(CreatePaymentAction::class)->execute($invoice);

        return [
            'invoice' => $invoice,
            'payment' => $payment,
        ];
    }
}
