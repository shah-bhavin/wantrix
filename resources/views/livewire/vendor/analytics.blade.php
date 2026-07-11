<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Analytics</h1>
        <p class="text-slate-500 mt-2">Campaign performance overview.</p>
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Campaigns</div>
            <div class="text-3xl font-bold">{{ $data['campaigns'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Contacts</div>
            <div class="text-3xl font-bold">{{ $data['contacts'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Messages</div>
            <div class="text-3xl font-bold">{{ $data['messages'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Failed</div>
            <div class="text-3xl font-bold text-red-600">{{ $data['failed'] }}</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Delivery Rate</div>
            <div class="text-4xl font-bold text-green-600 mt-2">{{ $data['delivery_rate'] }}%</div>
        </div>

        <div class="bg-white rounded-2xl border p-6">
            <div class="text-slate-500">Read Rate</div>
            <div class="text-4xl font-bold text-blue-600 mt-2">{{ $data['read_rate'] }}%</div>
        </div>
    </div>
</div>
