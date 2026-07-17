<div class="max-w-6xl mx-auto" @if($this->shouldPoll()) wire:poll.2s="refreshCampaign" @endif>

    <div wire:loading.flex wire:target="generateMessages,sendCampaign,pauseCampaign,resumeCampaign,cancelCampaign"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm items-center justify-center z-50">
        <div class="bg-white rounded-2xl px-8 py-6 shadow-xl">
            <svg class="animate-spin h-8 w-8 mx-auto text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <p class="mt-4 text-slate-700">
                Processing campaign...
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <x-vendor.campaign.header :campaign="$campaign" />


        <div class="border-b mt-8 mb-8">

            <nav class="flex gap-8">

                <button wire:click="changeTab('overview')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'overview',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'overview',
                    ])>

                    Overview

                </button>

                <button wire:click="changeTab('messages')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'messages',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'messages',
                    ])>

                    Messages

                </button>

                <button wire:click="changeTab('analytics')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'analytics',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'analytics',
                    ])>

                    Analytics

                </button>

                <button wire:click="changeTab('activity')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'activity',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'activity',
                    ])>

                    Activity

                </button>

            </nav>

        </div>

        <x-vendor.campaign.actions :campaign="$campaign" />

        @if($tab === 'overview')

        <livewire:vendor.campaign-overview :campaign="$campaign" :key="'overview-'.$campaign->id" />

        @endif


        @if($tab === 'messages')

        <livewire:vendor.campaign-messages :campaign="$campaign" :key="'messages-'.$campaign->id" />

        @endif


        @if($tab === 'analytics')

        Coming Soon

        @endif


        @if($tab === 'activity')

        <x-vendor.campaign.timeline :timeline="$timeline" />

        @endif


        {{--
        <livewire:vendor.message-drawer /> --}}
    </div>

</div>