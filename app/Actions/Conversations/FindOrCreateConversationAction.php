<?php

namespace App\Actions\Conversations;

use App\Models\Contact;
use App\Models\Conversation;
use App\Models\WhatsappAccount;

class FindOrCreateConversationAction
{
    public function execute(
        Contact $contact,
        ?WhatsappAccount $account
    ): Conversation {

        return Conversation::firstOrCreate(

            [

                'vendor_id' => $contact->vendor_id,

                'contact_id' => $contact->id,

                'whatsapp_account_id' => $account?->id,

            ],

            [

                'last_message_at' => now(),

                'last_incoming_at' => null,

                'last_outgoing_at' => null,

                'unread_count' => 0,

                'is_closed' => false,

                'is_ai_enabled' => false,

            ]

        );

    }
}