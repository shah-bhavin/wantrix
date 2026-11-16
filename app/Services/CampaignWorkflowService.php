<?php

namespace App\Services;

use App\Actions\Campaigns\GenerateCampaignMessagesAction;
use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignCompleted;
use App\Events\CampaignPaused;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;

class CampaignWorkflowService
{
    public function __construct(
        protected MessageStatusService $statusService
    ) {}

    public function generateMessages(Campaign $campaign): void
    {
        if (! $campaign->canGenerateMessages()) {
            throw new \Exception('Messages already generated.');
        }

        app(GenerateCampaignMessagesAction::class)
            ->execute($campaign);
    }

    public function send(Campaign $campaign): void
    {
        //
    }

    public function pause(Campaign $campaign): void
    {
        $campaign->refresh();

        if (! $campaign->status->canPause()) {
            return;
        }

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PROCESSING)
            ->update([
                'status' => CampaignStatus::PAUSED,
            ]);

        if ($updated === 1) {
            event(new CampaignPaused($campaign->fresh()));
        }
    }

    public function resume(Campaign $campaign): void
    {
        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Only paused campaigns can be resumed
        |--------------------------------------------------------------------------
        */

        if ($campaign->status !== CampaignStatus::PAUSED) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Atomic State Transition
        |--------------------------------------------------------------------------
        */

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PAUSED)
            ->update([
                'status' => CampaignStatus::PROCESSING,
            ]);

        if ($updated !== 1) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Queue Pending Messages
        |
        | Queued jobs from before the pause will continue automatically
        | when they wake up and see PROCESSING status.
        |--------------------------------------------------------------------------
        */

        $campaign = $campaign->fresh();

        $campaign
            ->messages()
            ->where('status', MessageStatus::PENDING)
            ->chunkById(500, function ($messages) {

                foreach ($messages as $message) {

                    $this->statusService->change(
                        $message,
                        MessageStatus::QUEUED
                    );

                    ProcessMessageJob::dispatch($message)
                        ->onQueue('messages');
                }
            });
    }

    public function cancel(Campaign $campaign): void
    {
        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Only cancellable campaigns can be cancelled
        |--------------------------------------------------------------------------
        */

        if (! $campaign->status->canCancel()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Atomic State Transition
        |--------------------------------------------------------------------------
        */

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->whereIn('status', [
                CampaignStatus::DRAFT,
                CampaignStatus::SCHEDULED,
                CampaignStatus::PROCESSING,
                CampaignStatus::PAUSED,
            ])
            ->update([
                'status' => CampaignStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);

        if ($updated !== 1) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Messages That Were Never Sent
        |--------------------------------------------------------------------------
        */

        $campaign = $campaign->fresh();

        $campaign
            ->messages()
            ->whereIn('status', [
                MessageStatus::PENDING,
                MessageStatus::QUEUED,
            ])
            ->chunkById(500, function ($messages) {

                foreach ($messages as $message) {

                    $this->statusService->change(
                        $message,
                        MessageStatus::FAILED,
                        [
                            'failure_reason' =>
                            'Campaign was cancelled before message was sent.',
                        ]
                    );
                }
            });
    }

    public function complete(Campaign $campaign): void
    {
        $campaign->refresh();

        /*
        |--------------------------------------------------------------------------
        | Only a processing campaign can become completed
        |--------------------------------------------------------------------------
        */

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Must Have Messages
        |--------------------------------------------------------------------------
        */

        if ($campaign->messages()->doesntExist()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Wait Until All Messages Reach A Final State
        |--------------------------------------------------------------------------
        */

        if (! $campaign->isReadyToComplete()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Atomic State Transition
        |--------------------------------------------------------------------------
        */

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PROCESSING)
            ->update([
                'status' => CampaignStatus::COMPLETED,
                'completed_at' => now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Dispatch Completion Event Only Once
        |--------------------------------------------------------------------------
        */

        if ($updated === 1) {

            event(new CampaignCompleted(
                $campaign->fresh()
            ));
        }
    }

    public function retryFailedMessages(
        Campaign $campaign,
        array $messageIds
    ): int {

        $campaign->refresh();

        $messages = $campaign->messages()
            ->whereIn('id', $messageIds)
            ->where('status', MessageStatus::FAILED)
            ->get()
            ->filter(fn($message) => $message->canRetry());

        if ($messages->isEmpty()) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Reopen Completed Campaign
        |--------------------------------------------------------------------------
        */

        if ($campaign->status === CampaignStatus::COMPLETED) {

            $campaign->update([
                'status' => CampaignStatus::PROCESSING,
                'completed_at' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Queue Failed Messages
        |--------------------------------------------------------------------------
        */

        foreach ($messages as $message) {

            $changed = $this->statusService->change(
                $message,
                MessageStatus::QUEUED,
                [
                    'failure_reason' => null,
                    'retry_count' => $message->retry_count + 1,
                    'last_retried_at' => now(),
                ]
            );

            if (! $changed) {
                continue;
            }

            ProcessMessageJob::dispatch($message->fresh())
                ->onQueue('messages');
        }

        return $messages->count();
    }
}
