@props([
    'title',
    'value',
    'percentage' => null,
])

<div class="bg-white rounded-2xl border border-slate-200 p-6">
    <p class="text-sm text-slate-500">{{ $title }}</p>
    <h3 class="mt-3 text-3xl font-bold">{{ $value }}</h3>
    <div class="h-2 rounded-full bg-amber-500" style="width: {{ $percentage }}%"></div>
</div>
