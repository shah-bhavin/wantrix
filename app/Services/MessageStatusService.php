<?php

namespace App\Services;

use App\Enums\MessageStatus;
use App\Models\Message;

class MessageStatusService
{
    public function change(
        Message $message,
        MessageStatus $status,
        array $attributes = []
    ): bool {

        $message->refresh();

        $oldStatus = $message->status;

        /*
        |--------------------------------------------------------------------------
        | Update Message Attributes
        |--------------------------------------------------------------------------
        */

        $message->update([
            ...$attributes,
            'status' => $status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Status History Only When Status Changes
        |--------------------------------------------------------------------------
        */

        if ($oldStatus !== $status) {

            $message->statusHistories()->create([
                'status' => $status,
                'changed_at' => now(),
            ]);
        }

        return true;
    }
}