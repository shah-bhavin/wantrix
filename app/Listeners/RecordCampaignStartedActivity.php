<?php

namespace App\Listeners;

use App\Enums\CampaignActivityType;
use App\Events\CampaignStarted;
use App\Models\CampaignActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordCampaignStartedActivity
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
    public function handle(CampaignStarted $event): void
    {
        $campaign = $event->campaign;

        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::STARTED,
            'description' => 'Campaign processing started.',
        ]);
    }
}
