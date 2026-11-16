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
        | Campaign Must Be Ready To Dispatch
        |--------------------------------------------------------------------------
        |
        | Manual dispatch:
        | DRAFT → PROCESSING
        |
        | Scheduled dispatch:
        | SCHEDULED → PROCESSING
        |--------------------------------------------------------------------------
        */

        if (! in_array(
            $campaign->status,
            [
                CampaignStatus::DRAFT,
                CampaignStatus::SCHEDULED,
            ],
            true
        )) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Must Have Generated Messages
        |--------------------------------------------------------------------------
        */

        if (
            $campaign->messages_generated_at === null
            || $campaign->messages()->doesntExist()
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Atomic State Protection
        |--------------------------------------------------------------------------
        |
        | Only one process can successfully change the campaign
        | to PROCESSING.
        |--------------------------------------------------------------------------
        */

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->whereIn('status', [
                CampaignStatus::DRAFT,
                CampaignStatus::SCHEDULED,
            ])
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