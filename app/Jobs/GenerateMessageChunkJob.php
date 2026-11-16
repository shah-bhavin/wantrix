<?php

namespace App\Jobs;

use App\Actions\Conversations\FindOrCreateConversationAction;
use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Models\Campaign;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

        /*
        |--------------------------------------------------------------------------
        | Refresh Campaign And Required Relations
        |--------------------------------------------------------------------------
        */

        $campaign = Campaign::with([
            'group',
            'template',
            'whatsappTemplate',
            'media',
            'whatsappAccount',
        ])->find($this->campaign->id);

        if (! $campaign) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Contacts
        |--------------------------------------------------------------------------
        */

        if (! $campaign->group) {
            return;
        }

        $contacts = $campaign
            ->group
            ->contacts()
            ->whereIn('contacts.id', $this->contactIds)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Resolve Message Data Once
        |--------------------------------------------------------------------------
        */

        $messageType = $this->resolveMessageType($campaign);
        $messageBody = $this->resolveMessageBody($campaign);

        /*
        |--------------------------------------------------------------------------
        | Create Messages
        |--------------------------------------------------------------------------
        */

        foreach ($contacts as $contact) {

            if ($this->batch()?->cancelled()) {
                return;
            }

            $conversation = app(
                FindOrCreateConversationAction::class
            )->execute(
                $contact,
                $campaign->whatsappAccount
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Message Creation
            |--------------------------------------------------------------------------
            */

            $alreadyExists = $campaign
                ->messages()
                ->where('contact_id', $contact->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $message = $campaign
                ->messages()
                ->create([

                    'vendor_id' => $campaign->vendor_id,

                    'campaign_id' => $campaign->id,

                    'conversation_id' => $conversation->id,

                    'contact_id' => $contact->id,

                    'direction' => 'outgoing',

                    'whatsapp_account_id' =>
                    $campaign->whatsapp_account_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Local / Meta Template
                    |--------------------------------------------------------------------------
                    */

                    'whatsapp_template_id' =>
                        $campaign->whatsapp_template_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Media
                    |--------------------------------------------------------------------------
                    */

                    'media_library_id' =>
                    $campaign->media_library_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Message Content
                    |--------------------------------------------------------------------------
                    */

                    'type' => $messageType,

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |
                    | messages.body is NOT NULL in database.
                    | For media-only messages we store an empty string.
                    |--------------------------------------------------------------------------
                    */

                    'body' => $messageBody ?? '',

                    'template_variables' => [],

                    'status' => MessageStatus::PENDING,

                ]);

            $conversation->touchOutgoing();

            $message->statusHistories()->create([
                'status' => MessageStatus::PENDING,
                'changed_at' => now(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Message Type
    |--------------------------------------------------------------------------
    */

    protected function resolveMessageType(
        Campaign $campaign
    ): MessageType {

        /*
    |--------------------------------------------------------------------------
    | Meta / WhatsApp Approved Template
    |--------------------------------------------------------------------------
    */

        if ($campaign->whatsapp_template_id) {
            return MessageType::TEMPLATE;
        }

        /*
    |--------------------------------------------------------------------------
    | Direct / Media Campaign
    |--------------------------------------------------------------------------
    */

        if ($campaign->message_type instanceof MessageType) {
            return $campaign->message_type;
        }

        /*
    |--------------------------------------------------------------------------
    | Safe Fallback
    |--------------------------------------------------------------------------
    */

        return MessageType::TEXT;
    }
    /*
    |--------------------------------------------------------------------------
    | Resolve Message Body
    |--------------------------------------------------------------------------
    */

    protected function resolveMessageBody(
        Campaign $campaign
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Local Template Body
        |--------------------------------------------------------------------------
        */

        if ($campaign->template) {
            return $campaign->template->body ?? '';
        }

        /*
        |--------------------------------------------------------------------------
        | Direct Text Campaign
        |--------------------------------------------------------------------------
        */

        if (
            $campaign->message_type === MessageType::TEXT ||
            $campaign->message_type === 'text'
        ) {
            return $campaign->message_body ?? '';
        }

        /*
        |--------------------------------------------------------------------------
        | Media Campaign Caption / Body
        |--------------------------------------------------------------------------
        |
        | Video, Image, Document, Audio can have empty body.
        |--------------------------------------------------------------------------
        */

        return $campaign->message_body ?? '';
    }
}
