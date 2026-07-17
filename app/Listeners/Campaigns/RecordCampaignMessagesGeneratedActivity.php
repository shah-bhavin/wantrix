<?php

namespace App\Listeners\Campaigns;

use App\Enums\CampaignActivityType;
use App\Events\Campaigns\CampaignMessagesGenerated;
use App\Models\CampaignActivity;

class RecordCampaignMessagesGeneratedActivity
{
    public function handle(CampaignMessagesGenerated $event): void
    {
        $campaign = $event->campaign;

        CampaignActivity::create([
            'campaign_id' => $campaign->id,
            'type' => CampaignActivityType::MESSAGES_GENERATED,
            'description' => $campaign->messages()->count().' messages generated.',
        ]);
    }
}