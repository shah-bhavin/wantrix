<div class="mt-8">
    <div class="flex justify-between mb-2">
        <span class="text-sm text-slate-600">Campaign Progress</span>
        <span class="font-semibold">{{ $stats['progress'] }}%</span>
    </div>
    <div class="w-full bg-slate-200 rounded-full h-3">
        <div class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stats['progress'] }}%">
        </div>
    </div>
</div>