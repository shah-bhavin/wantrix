<div class="grid md:grid-cols-3 gap-6 mt-8">
    <div class="bg-slate-50 rounded-xl p-5">
        <div class="text-sm text-slate-500">Success Rate</div>
        <div class="text-3xl font-bold text-green-600">{{ $stats['success_rate'] }}%</div>
    </div>
    <div class="bg-slate-50 rounded-xl p-5">
        <div class="text-sm text-slate-500">Delivery Rate</div>
        <div class="text-3xl font-bold text-blue-600">{{ $stats['delivery_rate'] }}%</div>
    </div>
    <div class="bg-slate-50 rounded-xl p-5">
        <div class="text-sm text-slate-500">Read Rate</div>
        <div class="text-3xl font-bold text-purple-600">{{ $stats['read_rate'] }}%</div>
    </div>
</div>