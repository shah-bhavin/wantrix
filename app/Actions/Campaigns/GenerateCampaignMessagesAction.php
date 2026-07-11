<?php

namespace App\Actions\Campaigns;

use App\Enums\MessageStatus;
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

        $contacts = $campaign->group->contacts;

        foreach ($contacts as $contact) {

            Message::create([
                'vendor_id' => $campaign->vendor_id,
                'campaign_id' => $campaign->id,
                'contact_id' => $contact->id,
                'body' => $campaign->template->body,
                'status' => MessageStatus::PENDING,
            ]);
        }
    }
}
