<?php

namespace App\Livewire\Vendor;

use App\Models\Campaign;
use Livewire\Component;

class CampaignActivity extends Component
{
    public Campaign $campaign;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function render()
    {
        return view('livewire.vendor.campaign-activity', [
            'activities' => $this->campaign
                ->activities()
                ->latest()
                ->get(),
        ]);
    }
}