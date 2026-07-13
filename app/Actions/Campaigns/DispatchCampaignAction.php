<?php

namespace App\Actions\Campaigns;

use App\Models\Campaign;
use App\Services\CampaignDispatcherService;

class DispatchCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        app(CampaignDispatcherService::class)
            ->dispatch($campaign);
    }
}