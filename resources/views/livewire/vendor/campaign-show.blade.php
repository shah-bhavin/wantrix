<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <h1 class="text-3xl font-bold text-slate-900">{{ $campaign->name }}</h1>
        <button wire:click="generateMessages"
            class="mt-6 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl">
            Generate Messages
        </button>
        <button wire:click="sendCampaign" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl">
            Send Campaign
        </button>
        
        <div class="grid md:grid-cols-5 gap-6 mt-8">

            <div>
                <div class="text-sm text-slate-500">
                    Total Messages
                </div>

                <div class="text-2xl font-bold">
                    {{ $campaign->messages()->count() }}
                </div>
            </div>

            <div>
                <div class="text-sm text-slate-500">
                    Pending
                </div>

                <div class="text-2xl font-bold text-amber-600">
                    {{ $campaign->messages()->where('status','pending')->count() }}
                </div>
            </div>

            <div>
                <div class="text-sm text-slate-500">
                    Sent
                </div>

                <div class="text-2xl font-bold text-blue-600">
                    {{ $campaign->messages()->where('status','sent')->count() }}
                </div>
            </div>

            <div>
                <div class="text-sm text-slate-500">
                    Delivered
                </div>

                <div class="text-2xl font-bold text-green-600">
                    {{ $campaign->messages()->where('status','delivered')->count() }}
                </div>
            </div>

            <div>
                <div class="text-sm text-slate-500">
                    Failed
                </div>

                <div class="text-2xl font-bold text-red-600">
                    {{ $campaign->messages()->where('status','failed')->count() }}
                </div>
            </div>

        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mt-8 text-sm">
            <div>
                <div class="text-slate-500">Status</div>
                <div class="font-semibold text-slate-900 mt-1">{{ ucfirst($campaign->status) }}</div>
            </div>

            <div>
                <div class="text-slate-500">Group</div>
                <div class="font-semibold text-slate-900 mt-1">{{ $campaign->group->name }}</div>
            </div>

            <div>
                <div class="text-slate-500">Template</div>
                <div class="font-semibold text-slate-900 mt-1">{{ $campaign->template->name }}</div>
            </div>

            <div>
                <div class="text-slate-500">Audience Size</div>
                <div class="font-semibold text-slate-900 mt-1">
                    {{ $campaign->group->contacts_count ?? $campaign->group->contacts()->count() }}
                </div>
            </div>
        </div>

    </div>


</div>