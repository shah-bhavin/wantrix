<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGatewayInterface;

class PaymentManager
{
    public function gateway(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'razorpay' => app(RazorpayService::class),
            default => throw new \Exception('Gateway not supported'),
        };
    }
}
