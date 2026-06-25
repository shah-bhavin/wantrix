<?php

namespace App\Actions\Campaigns;

use App\Models\Campaign;
use App\Models\Message;

class GenerateCampaignMessagesAction
{
    public function execute(Campaign $campaign): void
    {
        $contacts = $campaign->group->contacts;

        foreach ($contacts as $contact) {
            Message::create([
                'vendor_id' => $campaign->vendor_id,
                'campaign_id' => $campaign->id,
                'contact_id' => $contact->id,
                'body' => $campaign->template->body,
                'status' => 'pending',
            ]);
        }
    }
}
