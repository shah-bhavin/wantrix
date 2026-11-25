<?php

namespace App\Jobs;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\CampaignWorkflowService;
use App\Services\MessageStatusService;
use App\Services\Whatsapp\WhatsappManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessMessageJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $deleteWhenMissingModels = true;
    public int $tries = 1;
    public bool $failOnTimeout = true;
    public int $timeout = 120;
    public int $uniqueFor = 180;

    public function __construct(public Message $message)
    {
        $this->onQueue('messages');
    }

    public function uniqueId(): string
    {
        return 'message-' . $this->message->id;
    }

    public function handle(
        WhatsappManager $manager,
        CampaignWorkflowService $workflow,
        MessageStatusService $statusService
    ): void {
        $this->message->refresh();
        $campaign = $this->message->campaign;

        if (! $campaign) {
            Log::warning('Campaign message has no campaign.', [
                'message_id' => $this->message->id,
            ]);
            return;
        }

        $campaign->refresh();

        if (in_array($this->message->status, [
            MessageStatus::SENT,
            MessageStatus::DELIVERED,
            MessageStatus::READ,
            MessageStatus::FAILED,
            MessageStatus::SENDING,
        ], true) || filled($this->message->provider_message_id)) {
            return;
        }

        if ($campaign->status === CampaignStatus::CANCELLED) {
            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                ['failure_reason' => 'Campaign was cancelled before message was sent.']
            );
            $workflow->complete($campaign->fresh());
            return;
        }

        if ($campaign->status === CampaignStatus::PAUSED) {
            $this->release(30);
            return;
        }

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        $claimed = $statusService->change(
            $this->message,
            MessageStatus::SENDING,
            ['failure_reason' => null]
        );

        if (! $claimed) {
            return;
        }

        $this->message->refresh();
        $campaign->refresh();

        // Re-check campaign state immediately before provider preparation.
        if ($campaign->status === CampaignStatus::CANCELLED) {
            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                ['failure_reason' => 'Campaign was cancelled before message was sent.']
            );
            $workflow->complete($campaign->fresh());
            return;
        }

        if ($campaign->status === CampaignStatus::PAUSED) {
            $statusService->change($this->message, MessageStatus::QUEUED);
            $this->release(30);
            return;
        }

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        $account = $campaign->whatsappAccount;

        if (! $account) {
            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                ['failure_reason' => 'Campaign has no WhatsApp account.']
            );
            $workflow->complete($campaign->fresh());
            return;
        }

        try {
            $provider = $manager->account($account);
            $this->message->load('media');

            if ($this->message->media && ! $this->message->media->uploaded_to_meta) {
                $upload = $provider->uploadMedia(
                    $account,
                    $this->message->media
                );

                if (! ($upload['success'] ?? false)) {
                    $statusService->change(
                        $this->message,
                        MessageStatus::FAILED,
                        [
                            'failure_reason' => $upload['error']
                                ?? 'Unable to upload media to WhatsApp.',
                        ]
                    );
                    $workflow->complete($campaign->fresh());
                    return;
                }

                $this->message->refresh()->load('media');
            }
        } catch (Throwable $exception) {
            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                [
                    'failure_reason' => 'Media/provider preparation failed: '
                        . $exception->getMessage(),
                ]
            );

            Log::error('Campaign message preparation failed.', [
                'message_id' => $this->message->id,
                'exception' => $exception->getMessage(),
            ]);

            $workflow->complete($campaign->fresh());
            return;
        }

        // Do not automatically retry an ambiguous network outcome.
        try {
            $response = $provider->send($this->message);
        } catch (Throwable $exception) {
            Log::critical('WhatsApp delivery outcome is unknown.', [
                'message_id' => $this->message->id,
                'campaign_id' => $campaign->id,
                'exception' => $exception->getMessage(),
            ]);
            return;
        }

        $messageId = $response['message_id'] ?? null;

        if (($response['success'] ?? false) && filled($messageId)) {
            $statusService->change(
                $this->message,
                MessageStatus::SENT,
                [
                    'sent_at' => now(),
                    'provider_message_id' => $messageId,
                    'failure_reason' => null,
                ]
            );
        } elseif ($response['success'] ?? false) {
            Log::critical('WhatsApp accepted request without returning message ID.', [
                'message_id' => $this->message->id,
                'campaign_id' => $campaign->id,
            ]);
            return;
        } else {
            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                [
                    'failure_reason' => $response['error']
                        ?? 'WhatsApp provider rejected the message.',
                ]
            );
        }

        $workflow->complete($campaign->fresh());
    }

    public function failed(Throwable $exception): void
    {
        Log::critical('Campaign message job failed; delivery requires reconciliation.', [
            'message_id' => $this->message->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
