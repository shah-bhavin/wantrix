<?php

namespace App\Services;

use App\Enums\CampaignStatus;
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
        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Dispatch Only Processing Campaigns
        |--------------------------------------------------------------------------
        */

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Fire Campaign Started Event
        |--------------------------------------------------------------------------
        */

        event(new CampaignStarted(
            $campaign->fresh()
        ));

        $delay = 0;

        /*
        |--------------------------------------------------------------------------
        | Queue Only Pending Messages
        |
        | This is the initial campaign dispatch.
        | retry_count must NOT be incremented here.
        |--------------------------------------------------------------------------
        */

        $campaign
            ->messages()
            ->where('status', MessageStatus::PENDING)
            ->chunkById(500, function ($messages) use (
                $campaign,
                &$delay
            ) {

                foreach ($messages as $message) {

                    /*
                    |--------------------------------------------------------------------------
                    | Move Message To Queue
                    |--------------------------------------------------------------------------
                    */

                    $changed = $this->statusService->change(
                        $message,
                        MessageStatus::QUEUED,
                        [
                            'failure_reason' => null,
                        ]
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Do Not Dispatch If Status Change Failed
                    |--------------------------------------------------------------------------
                    */

                    if (! $changed) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Dispatch Message Job
                    |--------------------------------------------------------------------------
                    */

                    ProcessMessageJob::dispatch($message)
                        ->onQueue('messages')
                        ->delay(
                            now()->addSeconds($delay)
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Apply Campaign Message Delay
                    |--------------------------------------------------------------------------
                    */

                    $delay += max(
                        0,
                        (int) $campaign->message_delay_seconds
                    );
                }
            });
    }
}
