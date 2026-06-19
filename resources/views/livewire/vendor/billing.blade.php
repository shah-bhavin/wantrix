<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Billing</h1>
        <p class="text-slate-500 mt-2">Manage your subscription and payments.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-vendor.billing-card>
            <h2 class="text-lg font-semibold mb-4">Current Plan</h2>
            <div class="space-y-3">
                <div>Plan: <strong>{{ $data['plan_name'] }}</strong></div>
                <div>Status: <strong>{{ $data['status'] }}</strong></div>
                <div>Days Left: <strong>{{ $data['days_left'] }}</strong></div>
            </div>
        </x-vendor.billing-card>

        <x-vendor.billing-card>
            <h2 class="text-lg font-semibold mb-4">Usage</h2>
            <p>User limits coming soon.</p>
            <a href="{{ route('vendor.billing.upgrade') }}" class="inline-flex items-center px-5 py-3 bg-amber-500 text-white rounded-xl font-medium">
                Upgrade Plan
            </a>

        </x-vendor.billing-card>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <a href="{{ route('vendor.billing.upgrade') }}" class="bg-white border rounded-2xl p-6 hover:border-amber-500">Upgrade Plan</a>
            <a href="{{ route('vendor.billing.invoices') }}" class="bg-white border rounded-2xl p-6 hover:border-amber-500">View Invoices</a>
            <a href="{{ route('vendor.billing.payments') }}" class="bg-white border rounded-2xl p-6 hover:border-amber-500">View Payments</a>
            <a href="{{ route('vendor.billing.history') }}" class="bg-white border rounded-2xl p-6 hover:border-amber-500">Subscription History</a>
        </div>

    </div>
</div>
