<?php

namespace App\Events\Campaigns;

use App\Models\Campaign;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignMessagesGenerated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Campaign $campaign
    ) {
    }
}