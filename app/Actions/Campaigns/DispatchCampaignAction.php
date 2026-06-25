<?php

namespace App\Actions\Campaigns;

use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;

class DispatchCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        foreach ($campaign->messages as $message) {
            //$pendingCount = ProcessMessageJob::dispatch($message);
            ProcessMessageJob::dispatchSync($message);
        }

    }   
}
