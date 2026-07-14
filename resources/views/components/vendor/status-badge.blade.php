{{-- @props([
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
</span> --}}

@props([
    'status'
])

@php

$status = $status instanceof \BackedEnum
    ? $status->value
    : strtolower($status);

$class = match($status){

    'pending' => 'bg-amber-100 text-amber-700',

    'queued' => 'bg-blue-100 text-blue-700',

    'sending' => 'bg-sky-100 text-sky-700',

    'sent' => 'bg-green-100 text-green-700',

    'delivered' => 'bg-emerald-100 text-emerald-700',

    'read' => 'bg-purple-100 text-purple-700',

    'failed' => 'bg-red-100 text-red-700',

    'paid' => 'bg-green-100 text-green-700',

    'refunded' => 'bg-cyan-100 text-cyan-700',

    'cancelled' => 'bg-slate-100 text-slate-700',

    default => 'bg-slate-100 text-slate-700',

};

$label = $status instanceof \BackedEnum
    ? $status->name
    : ucfirst($status);

@endphp

<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $class }}">

    {{ ucfirst($status) }}

</span>