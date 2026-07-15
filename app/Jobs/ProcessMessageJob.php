<?php

namespace App\Jobs;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\CampaignWorkflowService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMessageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message)
    {
    }

    public function handle(): void
    {
        $campaign = $this->message->campaign;

        if ($campaign->status->canPause()) {
            //
        }

        if ($campaign->status->isPaused()) {
            $this->release(30);
            return;
        }

        $this->message->update([
            'status' => MessageStatus::SENDING,
        ]);
        
        $service = app(WhatsAppService::class);
        $response = $service->send($this->message);

        if ($response['success']) {
            $this->message->update([
                'status' => MessageStatus::SENT,
                'sent_at' => now(),
                'provider_message_id' => $response['message_id'],
            ]);
        } else {
            $this->message->update([
                'status' => MessageStatus::FAILED,
            ]);
        }


        app(CampaignWorkflowService::class)->complete($this->message->campaign);
    }
}
