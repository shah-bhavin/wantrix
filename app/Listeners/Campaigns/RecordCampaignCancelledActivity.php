<?php

namespace App\Listeners\Campaigns;

use App\Enums\CampaignActivityType;
use App\Events\CampaignCancelled;
use App\Models\CampaignActivity;

class RecordCampaignCancelledActivity
{
    public function handle(CampaignCancelled $event): void
    {
        $campaign = $event->campaign;

        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::CANCELLED,
            'description' => 'Campaign was cancelled.',
        ]);
    }
}