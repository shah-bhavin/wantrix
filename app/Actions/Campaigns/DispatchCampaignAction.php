<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;

class DispatchCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->update([
            'status' => CampaignStatus::PROCESSING,
            'started_at' => now(),
        ]);

        foreach ($campaign->messages as $message) {
            $message->update([
                'status' => MessageStatus::QUEUED,
            ]);
            ProcessMessageJob::dispatchSync($message);
        }

    }   
}
