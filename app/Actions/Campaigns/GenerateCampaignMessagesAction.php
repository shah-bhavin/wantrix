<?php

namespace App\Actions\Campaigns;

use App\Enums\MessageStatus;
use App\Jobs\GenerateCampaignMessagesJob;
use App\Models\Campaign;
use App\Models\Message;

class GenerateCampaignMessagesAction
{
    public function execute(Campaign $campaign): void
    {
        if (!$campaign->group) {
            throw new \Exception('Campaign group not found.');
        }

        if (!$campaign->template) {
            throw new \Exception('Campaign template not found.');
        }

        GenerateCampaignMessagesJob::dispatch($campaign);
    }
}
