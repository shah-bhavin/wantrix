<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subscription = auth()->user()->vendor?->activeSubscription;

        if (!$subscription) {
            return redirect()->route('vendor.billing')->with('error', 'No active subscription found.');
        }

        if ($subscription->status === SubscriptionStatus::ACTIVE && $subscription->ends_at?->isPast()) {
            return redirect()
                ->route('vendor.billing')
                ->with('error', 'Your subscription has expired.');
        }


        return $next($request);
    }
}
