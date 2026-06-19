@props([
    'plan',
    'current' => false,
])

<div class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex justify-between">
        <h3 class="text-xl font-bold">{{ $plan->name }}</h3>
        @if($current)
            <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">Current Plan</span>
        @endif
    </div>

    <div class="mt-4">
        <div class="text-4xl font-bold">₹{{ number_format($plan->monthly_price) }}</div>
        {{-- <div class="text-slate-500">/{{ $plan->billing_cycle }}</div> --}}
        <div class="text-slate-500">/ Monthly</div>
    </div>

    <ul class="mt-6 space-y-2 text-sm">
        <li>Users: {{ $plan->is_unlimited_users ? 'Unlimited' : $plan->max_users }}</li>
        <li>Contacts: {{ number_format($plan->max_contacts) }}</li>
        <li>Campaigns: {{ $plan->max_campaigns }}</li>
    </ul>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
