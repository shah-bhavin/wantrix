@props([
    'status'
])

@php
$class = match(strtolower($status)) {
    'paid' => 'bg-emerald-100 text-emerald-700',
    'pending' => 'bg-amber-100 text-amber-700',
    'failed' => 'bg-red-100 text-red-700',
    'refunded' => 'bg-blue-100 text-blue-700',
    'cancelled' => 'bg-slate-100 text-slate-700',
    default => 'bg-slate-100 text-slate-700',
};
@endphp

<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $class }}">
    {{ ucfirst($status) }}
</span>
