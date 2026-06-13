<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //return $next($request);
        $subscription = $vendor->activeSubscription;

        if (! $subscription || ! $subscription->status->canAccessFeatures()) {
            return redirect()->route('billing');
        }

    }
}
