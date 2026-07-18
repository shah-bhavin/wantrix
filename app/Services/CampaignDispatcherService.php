<?php

namespace App\Services;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignStarted;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class CampaignDispatcherService
{
    public function dispatch(Campaign $campaign): void
    {
        event(new CampaignStarted(
            $campaign->fresh()
        ));

        $delay = 0;

        $campaign
            ->messages()
            ->where('status', MessageStatus::PENDING)
            ->chunkById(500, function ($messages) use ($campaign, &$delay) {

                foreach ($messages as $message) {

                    $message->update([
                        'status' => MessageStatus::QUEUED,
                    ]);

                    ProcessMessageJob::dispatch($message)
                        ->delay(
                            now()->addSeconds($delay)
                        );

                    $delay += $campaign->message_delay_seconds;
                }
            });
    }
}
