<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\WhatsAppService;

class ProcessMessageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message)
    {
    }

    public function handle(): void
    {
        $service = app(WhatsAppService::class);
        $response = $service->send($this->message);

        if ($response['success']) {
            $this->message->update([
                'status' => 'sent',
                'sent_at' => now(),
                'provider_message_id' => $response['message_id'],
            ]);
        } else {
            $this->message->update([
                'status' => 'failed',
            ]);
        }


        $campaign = $this->message->campaign;

        $pendingCount = $campaign->messages()
            ->where('status', 'pending')
            ->count();

        if ($pendingCount === 0) {
            $campaign->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        // logger()->info('Campaign Check', [
        //     'campaign_id' => $campaign->id,
        //     'pending_count' => $pendingCount,
        // ]);
    }
}
