<?php

namespace App\Jobs;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\CampaignWorkflowService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public bool $deleteWhenMissingModels = true;
    public function uniqueId(): string
    {
        return 'message-' . $this->message->id;
    }
    public function __construct(
        public Message $message
    ) {}

    public function handle(
        WhatsAppService $whatsapp,
        CampaignWorkflowService $workflow
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Reload Message
        |--------------------------------------------------------------------------
        */

        $this->message->refresh();

        /*
        |--------------------------------------------------------------------------
        | Load Campaign
        |--------------------------------------------------------------------------
        */

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

            $this->message->update([
                'status' => MessageStatus::FAILED,
                'failure_reason' => 'Campaign was cancelled before message was sent.',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Paused
        |--------------------------------------------------------------------------
        */

        if ($campaign->status === CampaignStatus::PAUSED) {

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
        | Mark Message As Sending
        |--------------------------------------------------------------------------
        */

        $this->message->update([
            'status' => MessageStatus::SENDING,
            'failure_reason' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send Message
        |--------------------------------------------------------------------------
        */

        $response = $whatsapp->send($this->message);

        /*
        |--------------------------------------------------------------------------
        | Successful Message
        |--------------------------------------------------------------------------
        */

        if ($response['success']) {

            $this->message->update([
                'status' => MessageStatus::SENT,
                'sent_at' => now(),
                'provider_message_id' => $response['message_id'],
                'failure_reason' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Failed Message
        |--------------------------------------------------------------------------
        */ else {

            $this->message->update([
                'status' => MessageStatus::FAILED,
                'failure_reason' => $response['error']
                    ?? 'Message failed to send.',
            ]);
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
}
