@props([
    'title',
    'value',
    'icon' => 'chart-bar',
    'color' => 'amber',
])

<div class="bg-white rounded-2xl border border-slate-200 p-5">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm text-slate-500">
                {{ $title }}
            </p>

            <h3 class="mt-2 text-3xl font-bold">
                {{ $value }}
            </h3>

        </div>

        <div class="w-12 h-12 rounded-xl bg-{{ $color }}-100 flex items-center justify-center">

            <x-dynamic-component
                :component="'heroicon-o-'.$icon"
                class="w-6 h-6 text-{{ $color }}-600"/>

        </div>

    </div>

</div>