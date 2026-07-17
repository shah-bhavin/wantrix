<div class="flex flex-wrap items-center gap-3 mt-6 mb-6">

    <button wire:click="generateMessages" wire:loading.attr="disabled" @disabled(! $campaign->canGenerateMessages())
        class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium disabled:opacity-50
        disabled:cursor-not-allowed">

        Generate Messages

    </button>

    <button wire:click="sendCampaign" wire:loading.attr="disabled" @disabled(! $campaign->canSend())
        class="px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-medium disabled:opacity-50
        disabled:cursor-not-allowed">

        Send Campaign

    </button>

    @if($campaign->canPause())
    <button wire:click="pauseCampaign" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-xl">
        Pause
    </button>
    @endif

    @if($campaign->canResume())
    <button wire:click="resumeCampaign" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">
        Resume
    </button>
    @endif

    @if($campaign->status->canCancel())

    <button wire:click="cancelCampaign" wire:confirm="Are you sure you want to cancel this campaign?"
        class="px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition">
        Cancel Campaign
    </button>

    @endif

</div>