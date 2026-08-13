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
    | Do not move backwards
    |--------------------------------------------------------------------------
    */

        $statusOrder = [
            MessageStatus::PENDING->value => 1,
            MessageStatus::SENDING->value => 2,
            MessageStatus::SENT->value => 3,
            MessageStatus::DELIVERED->value => 4,
            MessageStatus::READ->value => 5,
            MessageStatus::FAILED->value => 99,
        ];

        if (
            $oldStatus instanceof MessageStatus &&
            isset($statusOrder[$oldStatus->value], $statusOrder[$status->value]) &&
            $statusOrder[$status->value] < $statusOrder[$oldStatus->value]
        ) {
            return false;
        }

        /*
    |--------------------------------------------------------------------------
    | Same status
    |--------------------------------------------------------------------------
    |
    | Update additional attributes if supplied,
    | but do NOT create another history record.
    |
    */

        if (
            $oldStatus instanceof MessageStatus &&
            $oldStatus->value === $status->value
        ) {
            if ($attributes) {
                $message->update($attributes);
            }

            return true;
        }

        /*
    |--------------------------------------------------------------------------
    | Update message
    |--------------------------------------------------------------------------
    */

        $message->update([
            ...$attributes,
            'status' => $status,
        ]);

        /*
    |--------------------------------------------------------------------------
    | Create history
    |--------------------------------------------------------------------------
    */

        $message->statusHistories()->create([
            'status' => $status,
            'changed_at' => now(),
        ]);

        return true;
    }

    /**
     * Determine whether a message can move from one status to another.
     */
    protected function canTransition(
        MessageStatus $from,
        MessageStatus $to
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Explicit WhatsApp message state machine
        |--------------------------------------------------------------------------
        |
        | PENDING
        |   ↓
        | SENDING
        |   ↓
        | SENT
        |   ↓
        | DELIVERED
        |   ↓
        | READ
        |
        | FAILED can occur while a message is being processed/sent.
        |
        */

        return match ($from) {

            MessageStatus::PENDING => in_array(
                $to,
                [
                    MessageStatus::SENDING,
                    MessageStatus::FAILED,
                ],
                true
            ),

            MessageStatus::SENDING => in_array(
                $to,
                [
                    MessageStatus::SENT,
                    MessageStatus::FAILED,
                ],
                true
            ),

            MessageStatus::SENT => in_array(
                $to,
                [
                    MessageStatus::DELIVERED,
                    MessageStatus::FAILED,
                ],
                true
            ),

            MessageStatus::DELIVERED => in_array(
                $to,
                [
                    MessageStatus::READ,
                    MessageStatus::FAILED,
                ],
                true
            ),

            /*
            |--------------------------------------------------------------------------
            | READ is terminal
            |--------------------------------------------------------------------------
            |
            | Once Meta has confirmed READ, an old webhook must not move
            | the message backwards.
            |
            */

            MessageStatus::READ => false,

            /*
            |--------------------------------------------------------------------------
            | FAILED is terminal
            |--------------------------------------------------------------------------
            |
            | A failed message cannot later become SENT / DELIVERED / READ.
            |
            */

            MessageStatus::FAILED => false,

            default => false,
        };
    }
}
