<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Services\Payments\Contracts\PaymentGatewayInterface;

class RazorpayService implements PaymentGatewayInterface
{
    public function createOrder(Payment $payment): array
    {
        return [];
    }

    public function verifyPayment(array $payload): bool
    {
        return true;
    }

    public function refund(Payment $payment): bool
    {
        return true;
    }
}
