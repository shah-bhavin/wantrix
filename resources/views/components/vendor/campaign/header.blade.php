<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">{{ $campaign->name }}</h1>
    </div>
    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $campaign->status->badgeColor() }}">
        {{ $campaign->status->label() }}
    </span>
</div>