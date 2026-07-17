<?php

namespace App\Listeners\Campaigns;

use App\Enums\CampaignActivityType;
use App\Events\CampaignPaused;
use App\Models\CampaignActivity;

class RecordCampaignPausedActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CampaignPaused $event): void
    {
        $campaign = $event->campaign;

        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::PAUSED,
            'description' => 'Campaign processing paused.',
        ]);
    }
}
