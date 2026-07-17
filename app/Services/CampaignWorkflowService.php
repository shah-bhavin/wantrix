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
        //
    }

    public function cancel(Campaign $campaign): void
    {
        //
    }

    public function complete(Campaign $campaign): void
    {
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

        $messages = $campaign->messages()
            ->whereIn('id', $messageIds)
            ->where('status', MessageStatus::FAILED)
            ->get()
            ->filter(fn($message) => $message->canRetry());

        if ($messages->isEmpty()) {
            return 0;
        }

        foreach ($messages as $message) {

            $message->update([
                'status' => MessageStatus::QUEUED,
                'failure_reason' => null,
                'retry_count' => $message->retry_count + 1,
                'last_retried_at' => now(),
            ]);
        }

        return $messages->count();
    }
}
