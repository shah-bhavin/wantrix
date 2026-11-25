<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\MessageType;
use App\Jobs\GenerateCampaignMessagesJob;
use App\Models\Campaign;
use Illuminate\Validation\ValidationException;

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

        if ($campaign->status !== CampaignStatus::DRAFT) {
            throw ValidationException::withMessages([
                'campaign' => 'Only draft campaigns can generate messages.',
            ]);
        }

        if ($campaign->messages()->exists()) {
            throw ValidationException::withMessages([
                'campaign' => 'Messages have already been generated for this campaign.',
            ]);
        }

        if (! $campaign->group) {
            throw ValidationException::withMessages([
                'group_id' => 'Campaign group was not found.',
            ]);
        }

        if ((int) $campaign->group->vendor_id !== (int) $campaign->vendor_id) {
            throw ValidationException::withMessages([
                'group_id' => 'Selected group does not belong to this vendor.',
            ]);
        }

        if (! $campaign->whatsappAccount) {
            throw ValidationException::withMessages([
                'whatsapp_account_id' => 'WhatsApp account was not found.',
            ]);
        }

        if ((int) $campaign->whatsappAccount->vendor_id !== (int) $campaign->vendor_id) {
            throw ValidationException::withMessages([
                'whatsapp_account_id' => 'Selected WhatsApp account does not belong to this vendor.',
            ]);
        }

        if (! $campaign->whatsappAccount->is_active) {
            throw ValidationException::withMessages([
                'whatsapp_account_id' => 'Selected WhatsApp account is inactive.',
            ]);
        }

        if (! $campaign->group->contacts()
            ->where('contacts.vendor_id', $campaign->vendor_id)
            ->exists()) {
            throw ValidationException::withMessages([
                'group_id' => 'Selected group has no valid contacts.',
            ]);
        }

        switch ($campaign->message_type) {
            case MessageType::TEXT:

                if ($campaign->template) {
                    if (
                        (int) $campaign->template->vendor_id !== (int) $campaign->vendor_id
                        || ! $campaign->template->is_active
                    ) {
                        throw ValidationException::withMessages([
                            'template_id' => 'Selected local template is invalid or inactive.',
                        ]);
                    }
                } elseif (blank($campaign->message_body)) {
                    throw ValidationException::withMessages([
                        'message_body' => 'Text campaign requires a message body or an active local template.',
                    ]);
                }

                break;

            case MessageType::TEMPLATE:

                if (! $campaign->whatsappTemplate) {
                    throw ValidationException::withMessages([
                        'whatsapp_template_id' => 'WhatsApp template was not found.',
                    ]);
                }

                if (
                    (int) $campaign->whatsappTemplate->vendor_id !== (int) $campaign->vendor_id
                    || (int) $campaign->whatsappTemplate->whatsapp_account_id !== (int) $campaign->whatsapp_account_id
                ) {
                    throw ValidationException::withMessages([
                        'whatsapp_template_id' => 'Selected WhatsApp template does not belong to this vendor and account.',
                    ]);
                }

                if (! $campaign->whatsappTemplate->isApproved()) {
                    throw ValidationException::withMessages([
                        'whatsapp_template_id' => 'Only approved WhatsApp templates can be used.',
                    ]);
                }

                break;

            case MessageType::IMAGE:
            case MessageType::VIDEO:
            case MessageType::DOCUMENT:
            case MessageType::AUDIO:

                if (! $campaign->media) {
                    throw ValidationException::withMessages([
                        'media_library_id' => 'Campaign media was not found.',
                    ]);
                }

                if ((int) $campaign->media->vendor_id !== (int) $campaign->vendor_id) {
                    throw ValidationException::withMessages([
                        'media_library_id' => 'Selected media does not belong to this vendor.',
                    ]);
                }

                if ($campaign->media->type?->value !== $campaign->message_type->value) {
                    throw ValidationException::withMessages([
                        'media_library_id' => 'Selected media type does not match the campaign message type.',
                    ]);
                }

                break;

            default:

                throw ValidationException::withMessages([
                    'message_type' => 'Unsupported campaign message type.',
                ]);
        }

        GenerateCampaignMessagesJob::dispatch($campaign)
            ->onQueue('campaign-generation');
    }
}
