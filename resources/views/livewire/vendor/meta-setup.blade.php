<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Meta Cloud API Setup</h1>
        <p class="text-slate-500 mt-2">Configure and verify your WhatsApp Business API connection.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <h2 class="font-semibold text-lg mb-4">Active Account</h2>
            @if($account)
                <div class="space-y-3">
                    <div>
                        <div class="text-xs text-slate-500">Account Name</div>
                        <div class="font-medium">{{ $account->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Phone Number</div>
                        <div class="font-medium">{{ $account->phone_number }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Provider</div>
                        <div class="font-medium">{{ ucfirst($account->provider) }}</div>
                    </div>
                </div>
            @else
                <div class="text-slate-400">No active WhatsApp account selected.</div>
            @endif
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <h2 class="font-semibold text-lg mb-4">Webhook Information</h2>
            <div class="space-y-4">
                <div>
                    <div class="text-xs text-slate-500 mb-1">Webhook URL</div>
                    <input readonly value="{{ url('/webhooks/meta') }}" class="w-full rounded-xl bg-slate-50 border-slate-200">
                </div>
                <div>
                    <div class="text-xs text-slate-500 mb-1">Verify Token</div>
                    <input readonly value="{{ config('services.meta.verify_token') }}" class="w-full rounded-xl bg-slate-50 border-slate-200">
                </div>
            </div>
        </div>
    </div>
</div>
