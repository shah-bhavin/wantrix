<?php

namespace App\Services;

use App\Enums\MessageStatus;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageStatusService
{
    public function change(
        Message $message,
        MessageStatus $status,
        array $attributes = []
    ): bool {
        return DB::transaction(function () use ($message, $status, $attributes) {
            $locked = Message::query()
                ->whereKey($message->getKey())
                ->lockForUpdate()
                ->first();

            if (! $locked || ! $locked->status instanceof MessageStatus) {
                return false;
            }

            $oldStatus = $locked->status;

            // Never allow a second worker to claim an already-sending message.
            if (
                $oldStatus === MessageStatus::SENDING
                && $status === MessageStatus::SENDING
            ) {
                return false;
            }

            if ($oldStatus === $status) {
                if ($attributes !== []) {
                    $locked->update($attributes);
                }

                $message->refresh();

                return true;
            }

            if (! $this->canTransition($oldStatus, $status)) {
                return false;
            }

            $locked->update([
                ...$attributes,
                'status' => $status,
            ]);

            $locked->statusHistories()->create([
                'status' => $status,
                'changed_at' => now(),
            ]);

            $message->refresh();

            return true;
        }, 3);
    }

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
                MessageStatus::DELIVERED,
                MessageStatus::READ,
                MessageStatus::FAILED,
            ], true),

            MessageStatus::SENT => in_array($to, [
                MessageStatus::DELIVERED,
                MessageStatus::READ,
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
