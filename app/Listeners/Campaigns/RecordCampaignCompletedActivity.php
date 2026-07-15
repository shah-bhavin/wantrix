<?php

namespace App\Listeners\Campaigns;

use App\Events\CampaignCompleted;
use Illuminate\Support\Facades\Log;

class RecordCampaignCompletedActivity
{
    public function handle(CampaignCompleted $event): void
    {
        Log::info('CampaignCompleted listener executed', [
            'campaign_id' => $event->campaign->id,
            'campaign_name' => $event->campaign->name,
        ]);
    }
}