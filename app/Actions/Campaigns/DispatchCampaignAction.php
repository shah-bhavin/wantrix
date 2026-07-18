<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Services\CampaignDispatcherService;

class DispatchCampaignAction
{
    public function execute(Campaign $campaign): bool
    {
        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Only scheduled campaigns can be dispatched
        |--------------------------------------------------------------------------
        */

        if ($campaign->status !== CampaignStatus::SCHEDULED) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Atomic State Protection
        |--------------------------------------------------------------------------
        */

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::SCHEDULED)
            ->update([
                'status' => CampaignStatus::PROCESSING,
                'started_at' => now(),
                'completed_at' => null,
                'cancelled_at' => null,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Another Process Already Started This Campaign
        |--------------------------------------------------------------------------
        */

        if ($updated !== 1) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Dispatch Messages
        |--------------------------------------------------------------------------
        */

        app(CampaignDispatcherService::class)
            ->dispatch($campaign->fresh());

        return true;
    }
}