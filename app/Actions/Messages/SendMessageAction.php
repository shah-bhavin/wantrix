<?php

namespace App\Actions\Messages;

use App\Enums\MessageStatus;
use App\Jobs\SendMessageJob;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class SendMessageAction
{
    public function execute(
        Conversation $conversation,
        string $body
    ): Message {
        return DB::transaction(function () use (
            $conversation,
            $body
        ) {

            $message = $conversation->outgoingMessages()->create([

                'vendor_id' => $conversation->vendor_id,

                'campaign_id' => null,

                'contact_id' => $conversation->contact_id,

                'whatsapp_account_id' =>
                $conversation->whatsapp_account_id,

                'body' => $body,

                'type' => 'text',

                'status' => MessageStatus::PENDING,

                'provider_message_id' => null,

                'retry_count' => 0,

            ]);

            $message->statusHistories()->create([

                'status' => MessageStatus::PENDING,

                'changed_at' => now(),

            ]);

            $conversation->touchOutgoing();

            SendMessageJob::dispatch($message->id);
            return $message;
        });
    }
}
