<div wire:poll.2s="loadStats">

    <x-vendor.campaign.dates
        :campaign="$campaign"
    />

    <x-vendor.campaign.statistics
        :stats="$stats"
    />

    <x-vendor.campaign.progress
        :stats="$stats"
    />

    <x-vendor.campaign.rates
        :stats="$stats"
    />

    <x-vendor.campaign.information
        :campaign="$campaign"
    />

</div>