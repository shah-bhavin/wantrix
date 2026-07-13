<div class="max-w-6xl mx-auto" @if($this->shouldPoll()) wire:poll.2s="refreshCampaign" @endif>

    <div wire:loading.flex wire:target="generateMessages,sendCampaign,pauseCampaign,resumeCampaign"
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
        <x-vendor.campaign.actions :campaign="$campaign" />
        <x-vendor.campaign.dates :campaign="$campaign" />
        <x-vendor.campaign.statistics :stats="$stats" />
        <x-vendor.campaign.progress :stats="$stats" />
        <x-vendor.campaign.rates :stats="$stats" />
        <x-vendor.campaign.information :campaign="$campaign" />
        <x-vendor.campaign.timeline :timeline="$timeline" />
    </div>

</div>