<?php

namespace App\Livewire\Vendor;

use App\Models\Campaign;
use App\Services\CampaignStatisticsService;
use Livewire\Component;

class CampaignOverview extends Component
{
    public Campaign $campaign;

    public array $stats = [];

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;

        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->campaign->refresh();

        $this->stats = app(CampaignStatisticsService::class)
            ->get($this->campaign);
    }

    public function render()
    {
        return view('livewire.vendor.campaign-overview');
    }
}