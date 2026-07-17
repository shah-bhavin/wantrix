<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Events\CampaignPaused;
use App\Models\Campaign;

class PauseCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->refresh();

        if (! $campaign->status->canPause()) {
            return;
        }

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PROCESSING)
            ->update([
                'status' => CampaignStatus::PAUSED,
            ]);

        if ($updated === 1) {
            event(new CampaignPaused(
                $campaign->fresh()
            ));
        }
    }
}