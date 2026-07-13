<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Models\Campaign;

class ResumeCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        if (! $campaign->status->canResume()) {
            return;
        }

        $campaign->update([
            'status' => CampaignStatus::PROCESSING,
        ]);
    }
}