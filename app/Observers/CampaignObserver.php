<?php

namespace App\Observers;

use App\Enums\CampaignActivityType;
use App\Models\Campaign;
use App\Models\CampaignActivity;

class CampaignObserver
{
    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::CREATED,
            'description' => 'Campaign was created.',
        ]);
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "restored" event.
     */
    public function restored(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     */
    public function forceDeleted(Campaign $campaign): void
    {
        //
    }
}
