<?php

namespace App\Jobs;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Models\Message;
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


        $campaign = $this->message->campaign;

        $remaining = $campaign->messages()
            ->whereIn('status', [
                MessageStatus::PENDING,
                MessageStatus::QUEUED,
                MessageStatus::SENDING,
            ])
            ->count();

        if ($remaining === 0) {
            $campaign->update([
                'status' => CampaignStatus::COMPLETED,
                'completed_at' => now(),
            ]);
        }
    }
}
