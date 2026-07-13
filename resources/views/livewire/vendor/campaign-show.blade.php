<div class="max-w-6xl mx-auto"  @if($this->shouldPoll())
        wire:poll.2s="refreshCampaign"
    @endif>

    <div wire:loading.flex wire:target="generateMessages,sendCampaign"
     class="fixed inset-0 bg-black/20 backdrop-blur-sm items-center justify-center z-50">

    <div class="bg-white rounded-2xl px-8 py-6 shadow-xl">

        <svg
            class="animate-spin h-8 w-8 mx-auto text-amber-500"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24">

            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"/>

            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v8z"/>

        </svg>

        <p class="mt-4 text-slate-700">

            Processing campaign...

        </p>

    </div>

</div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $campaign->name }}</h1>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $campaign->status->badgeColor() }}">
                {{ $campaign->status->label() }}
            </span>
        </div>

        

        
        <div class="flex flex-wrap items-center gap-3 mt-6">

            <button
                wire:click="generateMessages"
                wire:loading.attr="disabled"
                @disabled($campaign->messages()->exists())
                class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium disabled:opacity-50 disabled:cursor-not-allowed">

                Generate Messages

            </button>

            <button
                wire:click="sendCampaign"
                wire:loading.attr="disabled"
                @disabled($campaign->status !== \App\Enums\CampaignStatus::DRAFT)
                class="px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-medium disabled:opacity-50 disabled:cursor-not-allowed">

                Send Campaign

            </button>

        </div>


        <div class="grid md:grid-cols-3 gap-6 mt-8">
            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Created</div>
                <div class="font-semibold mt-2">{{ $campaign->created_at->format('d M Y') }}</div>
                <div class="text-sm text-slate-500">{{ $campaign->created_at->format('h:i A') }}</div>
            </div>

            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Started</div>
                @if($campaign->started_at)
                    <div class="font-semibold mt-2">{{ $campaign->started_at->format('d M Y') }}</div>
                    <div class="text-sm text-slate-500">{{ $campaign->started_at->format('h:i A') }}</div>
                @else
                    <span class="text-slate-400">Not Started</span>
                @endif
            </div>

            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Completed</div>
                @if($campaign->completed_at)
                    <div class="font-semibold mt-2">{{ $campaign->completed_at->format('d M Y') }}</div>
                    <div class="text-sm text-slate-500">{{ $campaign->completed_at->format('h:i A') }}</div>
                @else
                    <span class="text-slate-400">Running...</span>
                @endif
            </div>
        </div>


        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6 mt-8">
            <x-vendor.stat-card title="Total" :value="$stats['total']" icon="chat-bubble-left-right" color="slate"/>
            <x-vendor.stat-card title="Pending" :value="$stats['pending']" icon="clock" color="amber"/>
            <x-vendor.stat-card title="Queued" :value="$stats['queued']" icon="queue-list" color="yellow"/>
            <x-vendor.stat-card title="Sending" :value="$stats['sending']" icon="paper-airplane" color="blue"/>
            <x-vendor.stat-card title="Delivered" :value="$stats['delivered']" icon="check-badge" color="emerald"/>
            <x-vendor.stat-card title="Read" :value="$stats['read']" icon="eye" color="purple"/>
            <x-vendor.stat-card title="Sent" :value="$stats['sent']" icon="check" color="green"/>
            <x-vendor.stat-card title="Failed" :value="$stats['failed']" icon="x-circle" color="red"/>
        </div>

        <div class="mt-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm text-slate-600">Campaign Progress</span>
                <span class="font-semibold">{{ $stats['progress'] }}%</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-3">
                <div class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stats['progress'] }}%"></div>
            </div>
        </div>        

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

        {{-- Activity Timeline --}}
        <div class="mt-12">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Campaign Activity</h2>

            <div class="space-y-6">
                @foreach($timeline as $activity)
                    <div class="flex gap-5">
                        {{-- Timeline Icon --}}
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center @class([
                                'bg-blue-100 text-blue-600' => $activity['color'] == 'blue',
                                'bg-amber-100 text-amber-600' => $activity['color'] == 'amber',
                                'bg-green-100 text-green-600' => $activity['color'] == 'green',
                                'bg-emerald-100 text-emerald-600' => $activity['color'] == 'emerald',
                            ])">
                                <x-dynamic-component :component="'heroicon-o-'.$activity['icon']" class="w-5 h-5"/>
                            </div>

                            @unless($loop->last)
                                <div class="w-px flex-1 bg-slate-200 mt-2"></div>
                            @endunless
                        </div>

                        {{-- Timeline Content --}}
                        <div class="pb-8">
                            <div class="font-semibold text-slate-900">{{ $activity['title'] }}</div>
                            <div class="text-slate-500 mt-1">{{ $activity['description'] }}</div>
                            <div class="text-sm text-slate-400 mt-2">
                                {{ $activity['time']->format('d M Y') }} • {{ $activity['time']->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        
        

   
        
    </div>

    
</div>