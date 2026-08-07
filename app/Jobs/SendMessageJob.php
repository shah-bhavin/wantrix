<?php

namespace App\Jobs;

use App\Models\Message;
use App\Services\Whatsapp\WhatsappManager;
use App\Enums\MessageStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMessageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message
    ) {
        $this->onQueue('whatsapp-sending');
    }

    public function handle(
        WhatsappManager $manager
    ): void {
        $message = $this->message->fresh();

        if (! $message) {
            return;
        }

        if ($message->status !== MessageStatus::PENDING) {
            return;
        }

        $message->update([
            'status' => MessageStatus::SENDING,
        ]);

        $message->statusHistories()->create([
            'status' => MessageStatus::SENDING,
            'changed_at' => now(),
        ]);

        $account = $message->whatsappAccount;

        if (! $account) {
            $this->markFailed(
                $message,
                'No WhatsApp account associated with this message.'
            );

            return;
        }

        try {

            $provider = $manager->account($account);

            $result = $provider->send($message);

            if (
                ! ($result['success'] ?? false)
            ) {
                $this->markFailed(
                    $message,
                    $result['error'] ?? 'WhatsApp provider failed.'
                );

                return;
            }

            $message->update([
                'status' => MessageStatus::SENT,
                'provider_message_id' =>
                    $result['message_id'] ?? null,
                'sent_at' => now(),
                'failure_reason' => null,
            ]);

            $message->statusHistories()->create([
                'status' => MessageStatus::SENT,
                'changed_at' => now(),
            ]);

        } catch (\Throwable $e) {

            $this->markFailed(
                $message,
                $e->getMessage()
            );

            throw $e;
        }
    }

    protected function markFailed(
        Message $message,
        string $reason
    ): void {
        $message->update([
            'status' => MessageStatus::FAILED,
            'failure_reason' => $reason,
        ]);

        $message->statusHistories()->create([
            'status' => MessageStatus::FAILED,
            'changed_at' => now(),
        ]);
    }
}