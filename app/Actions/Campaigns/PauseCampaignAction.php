<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Models\Campaign;

class PauseCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        if (! $campaign->status->canPause()) {
            return;
        }

        $campaign->update([
            'status' => CampaignStatus::PAUSED,
        ]);
    }
}