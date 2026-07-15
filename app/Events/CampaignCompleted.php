<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CampaignCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Campaign $campaign
    ) {
        
    }
    // public function handle(CampaignCompleted $event): void
    // {
    //     Log::info('CampaignCompleted listener executed', [
    //         'campaign_id' => $event->campaign->id,
    //         'campaign_name' => $event->campaign->name,
    //     ]);
    // }
}