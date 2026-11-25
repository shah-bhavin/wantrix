<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\MessageStatusService;
use App\Services\Whatsapp\WhatsappManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public bool $failOnTimeout = true;

    public int $timeout = 120;

    public function __construct(public int $messageId)
    {
        $this->onQueue('whatsapp-sending');
    }

    public function handle(
        WhatsappManager $manager,
        MessageStatusService $statusService
    ): void {
        $message = Message::query()
            ->with(['whatsappAccount', 'campaign.whatsappAccount'])
            ->find($this->messageId);

        if (! $message) {
            return;
        }

        if (in_array($message->status, [
            MessageStatus::SENDING,
            MessageStatus::SENT,
            MessageStatus::DELIVERED,
            MessageStatus::READ,
            MessageStatus::FAILED,
        ], true) || filled($message->provider_message_id)) {
            return;
        }

        /*
         * Atomic claim: only one job can move this message to SENDING.
         */
        $claimed = $statusService->change(
            $message,
            MessageStatus::SENDING,
            ['failure_reason' => null]
        );

        if (! $claimed) {
            return;
        }

        $message->refresh();

        $account = $message->whatsappAccount;

        if (! $account && $message->campaign) {
            $account = $message->campaign->whatsappAccount;
        }

        if (! $account) {
            $statusService->change(
                $message,
                MessageStatus::FAILED,
                [
                    'failure_reason' => 'No WhatsApp account associated with this message.',
                ]
            );

            return;
        }

        try {
            $provider = $manager->account($account);
            $response = $provider->send($message);
        } catch (Throwable $exception) {
            Log::critical('Direct WhatsApp delivery outcome is unknown.', [
                'message_id' => $message->id,
                'exception' => $exception->getMessage(),
            ]);

            // Do not retry an ambiguous provider request.
            return;
        }

        $providerMessageId = $response['message_id'] ?? null;

        if (
            ($response['success'] ?? false)
            && filled($providerMessageId)
        ) {
            $statusService->change(
                $message,
                MessageStatus::SENT,
                [
                    'provider_message_id' => $providerMessageId,
                    'sent_at' => now(),
                    'failure_reason' => null,
                ]
            );

            return;
        }

        if ($response['success'] ?? false) {
            // Success without a provider ID is an ambiguous result.
            Log::critical('Direct WhatsApp request succeeded without provider message ID.', [
                'message_id' => $message->id,
            ]);

            return;
        }

        $statusService->change(
            $message,
            MessageStatus::FAILED,
            [
                'failure_reason' => $response['error']
                    ?? 'WhatsApp provider rejected the message.',
            ]
        );
    }

    public function failed(Throwable $exception): void
    {
        // Keep the message status unchanged for safe reconciliation.
        Log::critical('Direct WhatsApp job failed; delivery requires reconciliation.', [
            'message_id' => $this->messageId,
            'exception' => $exception->getMessage(),
        ]);
    }
}
