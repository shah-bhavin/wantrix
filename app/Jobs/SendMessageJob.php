<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\Whatsapp\WhatsappManager;
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

        /*
        |--------------------------------------------------------------------------
        | Mark as SENDING
        |--------------------------------------------------------------------------
        */

        $message->update([
            'status' => MessageStatus::SENDING,
        ]);

        $message->statusHistories()->create([
            'status' => MessageStatus::SENDING,
            'changed_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | WhatsApp Account
        |--------------------------------------------------------------------------
        */

        $account = $message->whatsappAccount;

        if (! $account && $message->campaign) {
            $account = $message->campaign->whatsappAccount;
        }

        if (! $account) {
            $this->markFailed(
                $message,
                'No WhatsApp account associated with this message.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Send Message
        |--------------------------------------------------------------------------
        */

        try {
            $provider = $manager->account($account);

            $result = $provider->send($message);

            /*
            |--------------------------------------------------------------------------
            | Provider Failure
            |--------------------------------------------------------------------------
            */

            if (! ($result['success'] ?? false)) {

                $this->markFailed(
                    $message,
                    $result['error'] ?? 'WhatsApp provider failed.'
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

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

    /**
     * Mark message as failed.
     *
     * Meta API errors can be either strings or arrays.
     */
    protected function markFailed(
        Message $message,
        mixed $reason
    ): void {

        $reason = $this->normalizeFailureReason($reason);

        $message->update([
            'status' => MessageStatus::FAILED,
            'failure_reason' => $reason,
        ]);

        $message->statusHistories()->create([
            'status' => MessageStatus::FAILED,
            'changed_at' => now(),
        ]);
    }

    /**
     * Convert provider/API errors into a readable string.
     */
    protected function normalizeFailureReason(mixed $reason): string
    {
        if (is_string($reason)) {
            return $reason;
        }

        if (is_array($reason)) {
            return json_encode(
                $reason,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES |
                JSON_PRETTY_PRINT
            ) ?: 'Unknown provider error.';
        }

        if ($reason instanceof \Throwable) {
            return $reason->getMessage();
        }

        return (string) $reason;
    }
}