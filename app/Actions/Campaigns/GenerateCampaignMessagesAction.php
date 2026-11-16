<?php

namespace App\Actions\Campaigns;

use App\Enums\MessageType;
use App\Jobs\GenerateCampaignMessagesJob;
use App\Models\Campaign;

class GenerateCampaignMessagesAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->load([
            'group',
            'template',
            'whatsappTemplate',
            'media',
            'whatsappAccount',
        ]);

        if (! $campaign->group) {
            throw new \Exception('Campaign group not found.');
        }

        if (! $campaign->whatsappAccount) {
            throw new \Exception('WhatsApp account not found.');
        }

        switch ($campaign->message_type) {

            case MessageType::TEXT:

                if (
                    empty($campaign->message_body)
                    && ! $campaign->template
                ) {
                    throw new \Exception(
                        'Text campaign requires a direct message or local template.'
                    );
                }

                break;

            case MessageType::TEMPLATE:

                if (! $campaign->whatsappTemplate) {
                    throw new \Exception(
                        'Meta WhatsApp template not found.'
                    );
                }

                break;

            case MessageType::IMAGE:
            case MessageType::VIDEO:
            case MessageType::DOCUMENT:
            case MessageType::AUDIO:

                if (! $campaign->media) {
                    throw new \Exception(
                        'Campaign media not found.'
                    );
                }

                break;

            default:

                throw new \Exception(
                    'Unsupported campaign message type.'
                );
        }

        GenerateCampaignMessagesJob::dispatch($campaign);
    }
}