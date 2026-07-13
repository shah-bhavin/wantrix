<div class="grid md:grid-cols-3 gap-6 mt-8">
    <div>
        <div class="text-sm text-slate-500">Audience</div>
        <div class="text-xl font-bold">{{ $campaign->group->contacts()->count() }}</div>
    </div>
    <div>
        <div class="text-sm text-slate-500">Group</div>
        <div class="font-semibold">{{ $campaign->group->name }}</div>
    </div>
    <div>
        <div class="text-sm text-slate-500">Template</div>
        <div class="font-semibold">{{ $campaign->template->name }}</div>
    </div>
</div>