<?php

namespace App\Services\Payments\Contracts;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function createOrder(Payment $payment): array;

    public function verifyPayment(array $payload): bool;

    public function refund(Payment $payment): bool;
}
