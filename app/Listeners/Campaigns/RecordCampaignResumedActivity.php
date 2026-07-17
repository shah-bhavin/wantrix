<?php

namespace App\Listeners\Campaigns;

use App\Enums\CampaignActivityType;
use App\Events\CampaignResumed;
use App\Models\CampaignActivity;

class RecordCampaignResumedActivity
{
    public function handle(CampaignResumed $event): void
    {
        $campaign = $event->campaign;

        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::RESUMED,
            'description' => 'Campaign processing resumed.',
        ]);
    }
}