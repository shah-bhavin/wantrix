<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BillingStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $revenue = Payment::query()
            ->where('status', PaymentStatus::SUCCESS)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $active_subscription = Subscription::query()
            ->where('status', SubscriptionStatus::ACTIVE)
            ->count();
        
        $trial_subscription = Subscription::query()
            ->where('status', SubscriptionStatus::TRIAL)
            ->count();

        return [
            Stat::make(
                'Monthly Revenue',
                '₹' . number_format($revenue, 2)
            ),

            Stat::make(
                'Active Subscriptions',
                $active_subscription
            ),

            Stat::make(
                'Trial Subscriptions',
                $trial_subscription
            ),
            Stat::make(
                'Expiring Soon',
                Subscription::whereBetween('ends_at', [now(), now()->addDays(7)])->count(),
            ),
        ];
    }
}
