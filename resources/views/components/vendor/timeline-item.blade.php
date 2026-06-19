@props([
    'change'
])

<div class="relative pl-10">
    <div class="absolute left-0 top-2 w-4 h-4 rounded-full bg-amber-500"></div>
    <div class="absolute left-[7px] top-6 bottom-[-30px] w-[2px] bg-slate-200"></div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">
                {{ $change->oldPlan?->name ?? 'No Plan' }} → {{ $change->newPlan?->name ?? 'Unknown Plan' }}
            </h3>
            <span class="text-sm text-slate-500">
                {{ $change->created_at->format('d M Y') }}
            </span>
        </div>

        <div class="text-sm text-slate-600">
            {{ $change->reason ?? 'Subscription changed.' }}
        </div>
    </div>
</div>
