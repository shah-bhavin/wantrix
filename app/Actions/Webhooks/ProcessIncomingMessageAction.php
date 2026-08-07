<?php

namespace App\Actions\Webhooks;

use App\Actions\Conversations\FindOrCreateConversationAction;
use App\Enums\MessageType;
use App\Models\Contact;
use App\Models\IncomingMessage;
use App\Models\WhatsappAccount;

class ProcessIncomingMessageAction
{
    public function execute(array $value, array $message): void
    {
        /*
        |--------------------------------------------------------------------------
        | Find WhatsApp Account
        |--------------------------------------------------------------------------
        */

        $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;

        if (! $phoneNumberId) {
            return;
        }

        $account = WhatsappAccount::where(
            'phone_number_id',
            $phoneNumberId
        )->first();

        if (! $account) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Provider Message ID
        |--------------------------------------------------------------------------
        */

        $providerMessageId = $message['id'] ?? null;

        if (! $providerMessageId) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Webhook Processing
        |--------------------------------------------------------------------------
        */

        $alreadyExists = IncomingMessage::where(
            'whatsapp_account_id',
            $account->id
        )
            ->where(
                'provider_message_id',
                $providerMessageId
            )
            ->exists();

        if ($alreadyExists) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Contact
        |--------------------------------------------------------------------------
        */

        $phone = $message['from'] ?? null;

        if (! $phone) {
            return;
        }

        $contact = Contact::firstOrCreate(
            [
                'vendor_id' => $account->vendor_id,
                'phone_number' => $phone,
            ],
            [
                'name' => $phone,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Message Type
        |--------------------------------------------------------------------------
        */

        $type = $this->mapType(
            $message['type'] ?? 'unknown'
        );

        /*
        |--------------------------------------------------------------------------
        | Find / Create Conversation
        |--------------------------------------------------------------------------
        */

        $conversation = app(
            FindOrCreateConversationAction::class
        )->execute(
            $contact,
            $account
        );

        /*
        |--------------------------------------------------------------------------
        | Save Incoming Message
        |--------------------------------------------------------------------------
        */

        $incoming = IncomingMessage::create([
            'vendor_id' => $account->vendor_id,
            'contact_id' => $contact->id,
            'whatsapp_account_id' => $account->id,
            'provider_message_id' => $providerMessageId,
            'conversation_id' => $conversation->id,
            'from_phone' => $phone,
            'body' => $message['text']['body'] ?? null,
            'type' => $type,
            'payload' => $message,
            'received_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Conversation
        |--------------------------------------------------------------------------
        */

        $conversation->touchIncoming();

        /*
        |--------------------------------------------------------------------------
        | Incoming Media
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $message['type'] ?? null,
                [
                    'image',
                    'video',
                    'document',
                    'audio',
                    'sticker',
                ],
                true
            )
        ) {
            app(ProcessIncomingMediaAction::class)
                ->execute($incoming, $message);
        }
    }

    protected function mapType(string $type): string
    {
        return match ($type) {
            'text' => 'text',
            'image' => 'image',
            'document' => 'document',
            'video' => 'video',
            'audio' => 'audio',
            'sticker' => 'sticker',
            'button' => 'button',
            'interactive' => 'interactive',
            'location' => 'location',
            'contacts' => 'contact',
            'reaction' => 'reaction',
            default => 'unknown',
        };
    }
}