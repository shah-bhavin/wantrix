<?php

namespace App\Listeners\Campaigns;

use App\Events\Campaigns\CampaignMessagesGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCampaignGeneratedTimestamp
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
    public function handle(CampaignMessagesGenerated $event): void
    {
        $event->campaign->update([
            'messages_generated_at' => now(),
        ]);
    }
}
