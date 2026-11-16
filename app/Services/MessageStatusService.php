<?php

namespace App\Services;

use App\Enums\MessageStatus;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageStatusService
{
    /**
     * Change message status safely.
     *
     * The message update and its status history entry are
     * committed together, or neither is committed.
     */
    public function change(
        Message $message,
        MessageStatus $status,
        array $attributes = []
    ): bool {
        return DB::transaction(function () use (
            $message,
            $status,
            $attributes
        ): bool {
            $lockedMessage = Message::query()
                ->whereKey($message->getKey())
                ->lockForUpdate()
                ->first();

            if (! $lockedMessage) {
                return false;
            }

            $oldStatus = $lockedMessage->status;

            // Same status: update attributes without creating
            // a duplicate status-history entry.
            if ($oldStatus === $status) {
                if (! empty($attributes)) {
                    $lockedMessage->update($attributes);
                }

                $message->refresh();

                return true;
            }

            // Never permit an unknown or invalid transition.
            if (
                ! $oldStatus instanceof MessageStatus
                || ! $this->canTransition($oldStatus, $status)
            ) {
                return false;
            }

            $lockedMessage->update([
                ...$attributes,
                'status' => $status,
            ]);

            $lockedMessage->statusHistories()->create([
                'status' => $status,
                'changed_at' => now(),
            ]);

            // Keep the caller's model instance up to date.
            $message->refresh();

            return true;
        }, 3);
    }

    /**
     * Validate the permitted message status transitions.
     */
    protected function canTransition(
        MessageStatus $from,
        MessageStatus $to
    ): bool {
        return match ($from) {
            MessageStatus::PENDING => in_array($to, [
                MessageStatus::QUEUED,
                MessageStatus::SENDING,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::QUEUED => in_array($to, [
                MessageStatus::SENDING,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::SENDING => in_array($to, [
                MessageStatus::QUEUED,
                MessageStatus::SENT,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::SENT => in_array($to, [
                MessageStatus::DELIVERED,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::DELIVERED => in_array($to, [
                MessageStatus::READ,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::FAILED => $to === MessageStatus::QUEUED,

            MessageStatus::READ => false,
        };
    }
}
