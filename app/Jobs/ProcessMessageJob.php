<?php

namespace App\Jobs;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\CampaignWorkflowService;
use App\Services\MessageStatusService;
use App\Services\Whatsapp\WhatsappManager;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public bool $deleteWhenMissingModels = true;
    public int $tries = 3;
    public array $backoff = [10, 30, 60];
    public bool $failOnTimeout = true;
    public int $timeout = 120;

    public function uniqueId(): string
    {
        return 'message-' . $this->message->id;
    }

    public function __construct(
        public Message $message
    ) {
        $this->onQueue('messages');
    }

    public function handle(
        WhatsappManager $manager,
        CampaignWorkflowService $workflow,
        MessageStatusService $statusService
    ): void {

        $this->message->refresh();

        $campaign = $this->message->campaign;

        if (! $campaign) {
            return;
        }

        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Campaign Cancelled
        |--------------------------------------------------------------------------
        */

        if ($campaign->status === CampaignStatus::CANCELLED) {

            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                [
                    'failure_reason' =>
                    'Campaign was cancelled before message was sent.',
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Paused
        |--------------------------------------------------------------------------
        */

        if ($campaign->status === CampaignStatus::PAUSED) {

            $statusService->change(
                $this->message,
                MessageStatus::QUEUED
            );

            $this->release(30);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Must Be Processing
        |--------------------------------------------------------------------------
        */

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Ignore Already Processed Messages
        |--------------------------------------------------------------------------
        */

        if (in_array($this->message->status, [
            MessageStatus::SENT,
            MessageStatus::DELIVERED,
            MessageStatus::READ,
        ])) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Mark As Sending
        |--------------------------------------------------------------------------
        */

        $statusService->change(
            $this->message,
            MessageStatus::SENDING,
            [
                'failure_reason' => null,
            ]
        );

        /*
|--------------------------------------------------------------------------
| Re-check Campaign Before Sending
|--------------------------------------------------------------------------
*/

        $campaign->refresh();

        if ($campaign->status === CampaignStatus::CANCELLED) {

            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                [
                    'failure_reason' =>
                    'Campaign was cancelled before message was sent.',
                ]
            );

            return;
        }

        if ($campaign->status === CampaignStatus::PAUSED) {

            $statusService->change(
                $this->message,
                MessageStatus::QUEUED
            );

            $this->release(30);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Send Message
        |--------------------------------------------------------------------------
        */

        $provider = $manager->account(
            $campaign->whatsappAccount
        );

        $response = $provider->send(
            $this->message
        );

        /*
        |--------------------------------------------------------------------------
        | Successful Message
        |--------------------------------------------------------------------------
        */

        if ($response['success']) {

            $statusService->change(
                $this->message,
                MessageStatus::SENT,
                [
                    'sent_at' => now(),
                    'provider_message_id' => $response['message_id'],
                    'failure_reason' => null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Failed Message
        |--------------------------------------------------------------------------
        */ else {

            $statusService->change(
                $this->message,
                MessageStatus::FAILED,
                [
                    'failure_reason' => $response['error']
                        ?? 'Message failed to send.',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Campaign Completion
        |--------------------------------------------------------------------------
        */

        $workflow->complete(
            $campaign->fresh()
        );
    }
    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(5);
    }
    public function failed(\Throwable $exception): void
    {
        app(MessageStatusService::class)->change(
            $this->message,
            MessageStatus::FAILED,
            [
                'failure_reason' => $exception->getMessage()
                    ?: 'Message processing failed unexpectedly.',
            ]
        );
    }
}
