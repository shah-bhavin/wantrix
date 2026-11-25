<?php

namespace App\Jobs;

use App\Actions\Conversations\FindOrCreateConversationAction;
use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GenerateMessageChunkJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
        public Campaign $campaign,
        public array $contactIds,
    ) {
        $this->onQueue('campaign-generation');
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $campaign = Campaign::with([
            'group',
            'template',
            'whatsappTemplate',
            'media',
            'whatsappAccount',
        ])->find($this->campaign->id);

        if (! $campaign || ! $campaign->group) {
            return;
        }

        if ($campaign->status !== CampaignStatus::DRAFT) {
            return;
        }

        if (
            (int) $campaign->group->vendor_id !==
            (int) $campaign->vendor_id
        ) {
            return;
        }

        $contacts = $campaign->group
            ->contacts()
            ->whereIn('contacts.id', $this->contactIds)
            ->where('contacts.vendor_id', $campaign->vendor_id)
            ->get();

        $messageType = $this->resolveMessageType($campaign);
        $messageBody = $this->resolveMessageBody($campaign);

        foreach ($contacts as $contact) {
            if ($this->batch()?->cancelled()) {
                return;
            }

            if ($campaign->fresh()?->status !== CampaignStatus::DRAFT) {
                return;
            }

            try {
                DB::transaction(function () use (
                    $campaign,
                    $contact,
                    $messageType,
                    $messageBody
                ) {
                    /*
                     * Fast check. The database unique index below
                     * remains the final concurrency protection.
                     */
                    $exists = $campaign->messages()
                        ->where('contact_id', $contact->id)
                        ->exists();

                    if ($exists) {
                        return;
                    }

                    $conversation = app(
                        FindOrCreateConversationAction::class
                    )->execute(
                        $contact,
                        $campaign->whatsappAccount
                    );

                    $message = $campaign->messages()->create([
                        'vendor_id' => $campaign->vendor_id,
                        'campaign_id' => $campaign->id,
                        'conversation_id' => $conversation->id,
                        'contact_id' => $contact->id,
                        'direction' => 'outgoing',
                        'whatsapp_account_id' =>
                            $campaign->whatsapp_account_id,
                        'whatsapp_template_id' =>
                            $campaign->whatsapp_template_id,
                        'media_library_id' =>
                            $campaign->media_library_id,
                        'type' => $messageType,
                        'body' => $messageBody ?? '',
                        'template_variables' => [],
                        'status' => MessageStatus::PENDING,
                    ]);

                    $message->statusHistories()->create([
                        'status' => MessageStatus::PENDING,
                        'changed_at' => now(),
                    ]);

                    $conversation->touchOutgoing();
                }, 3);
            } catch (QueryException $exception) {
                /*
                 * Another worker may have inserted the same
                 * campaign/contact message at the same time.
                 * Ignore only this specific unique-index conflict.
                 */
                if (! $this->isCampaignContactDuplicate($exception)) {
                    throw $exception;
                }
            }
        }
    }

    protected function isCampaignContactDuplicate(
        QueryException $exception
    ): bool {
        $message = strtolower($exception->getMessage());

        return str_contains(
            $message,
            'messages_campaign_contact_unique'
        ) || str_contains(
            $message,
            'messages.campaign_id, messages.contact_id'
        );
    }

    protected function resolveMessageType(
        Campaign $campaign
    ): MessageType {
        if ($campaign->whatsapp_template_id) {
            return MessageType::TEMPLATE;
        }

        if ($campaign->message_type instanceof MessageType) {
            return $campaign->message_type;
        }

        return MessageType::TEXT;
    }

    protected function resolveMessageBody(
        Campaign $campaign
    ): string {
        if ($campaign->template) {
            return $campaign->template->body ?? '';
        }

        return $campaign->message_body ?? '';
    }
}
