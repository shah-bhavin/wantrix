<?php

namespace App\Services;

use App\Enums\MessageStatus;
use App\Events\CampaignStarted;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;

class CampaignDispatcherService
{
    public function __construct(
        protected MessageStatusService $statusService
    ) {}

    public function dispatch(Campaign $campaign): void
    {
        event(new CampaignStarted(
            $campaign->fresh()
        ));

        $delay = 0;

        $campaign
            ->messages()
            ->where('status', MessageStatus::PENDING)
            ->chunkById(500, function ($messages) use (
                $campaign,
                &$delay
            ) {

                foreach ($messages as $message) {

                    $this->statusService->change(
                        $message,
                        MessageStatus::QUEUED,
                        [
                            'failure_reason' => null,
                            'retry_count' => $message->retry_count + 1,
                            'last_retried_at' => now(),
                        ]
                    );

                    ProcessMessageJob::dispatch($message)
                        ->onQueue('messages')
                        ->delay(
                            now()->addSeconds($delay)
                        );

                    $delay += $campaign->message_delay_seconds;
                }
            });
    }
}
