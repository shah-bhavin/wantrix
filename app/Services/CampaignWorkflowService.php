<?php

namespace App\Services;

use App\Actions\Campaigns\CancelCampaignAction;
use App\Actions\Campaigns\DispatchCampaignAction;
use App\Actions\Campaigns\GenerateCampaignMessagesAction;
use App\Actions\Campaigns\PauseCampaignAction;
use App\Actions\Campaigns\ResumeCampaignAction;
use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignCompleted;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class CampaignWorkflowService
{
    public function __construct(
        protected MessageStatusService $statusService
    ) {}

    public function generateMessages(Campaign $campaign): void
    {
        if (! $campaign->canGenerateMessages()) {
            throw new \RuntimeException(
                'Messages already generated or campaign is not a draft.'
            );
        }

        app(GenerateCampaignMessagesAction::class)
            ->execute($campaign);
    }

    public function send(Campaign $campaign): void
    {
        app(DispatchCampaignAction::class)->execute($campaign);
    }

    public function pause(Campaign $campaign): void
    {
        app(PauseCampaignAction::class)->execute($campaign);
    }

    public function resume(Campaign $campaign): void
    {
        app(ResumeCampaignAction::class)->execute($campaign);
    }

    public function cancel(Campaign $campaign): void
    {
        app(CancelCampaignAction::class)->execute($campaign);
    }

    public function complete(Campaign $campaign): void
    {
        $campaign->refresh();

        if ($campaign->status !== CampaignStatus::PROCESSING) {
            return;
        }

        if ($campaign->messages()->doesntExist()) {
            return;
        }

        if (! $campaign->isReadyToComplete()) {
            return;
        }

        $updated = Campaign::query()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PROCESSING)
            ->update([
                'status' => CampaignStatus::COMPLETED,
                'completed_at' => now(),
            ]);

        if ($updated === 1) {
            event(new CampaignCompleted($campaign->fresh()));
        }
    }

    public function retryFailedMessages(
        Campaign $campaign,
        array $messageIds
    ): int {
        $messageIds = array_values(array_unique(array_filter(
            array_map('intval', $messageIds),
            fn (int $id) => $id > 0
        )));

        if (empty($messageIds)) {
            return 0;
        }

        return DB::transaction(function () use ($campaign, $messageIds) {
            $lockedCampaign = Campaign::query()
                ->whereKey($campaign->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedCampaign) {
                return 0;
            }

            /*
             * Cancelled, draft and scheduled campaigns cannot
             * retry failed messages.
             */
            if (! in_array($lockedCampaign->status, [
                CampaignStatus::PROCESSING,
                CampaignStatus::PAUSED,
                CampaignStatus::COMPLETED,
                CampaignStatus::FAILED,
            ], true)) {
                return 0;
            }

            /*
             * Select only failed messages belonging to this campaign
             * and still within the retry limit.
             */
            $messages = Message::query()
                ->where('campaign_id', $lockedCampaign->id)
                ->whereIn('id', $messageIds)
                ->where('status', MessageStatus::FAILED)
                ->where('retry_count', '<', Message::MAX_RETRIES)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($messages->isEmpty()) {
                return 0;
            }

            /*
             * Reopen completed or failed campaigns only when
             * there are actually eligible messages to retry.
             */
            if (in_array($lockedCampaign->status, [
                CampaignStatus::COMPLETED,
                CampaignStatus::FAILED,
            ], true)) {
                $lockedCampaign->update([
                    'status' => CampaignStatus::PROCESSING,
                    'completed_at' => null,
                ]);
            }

            $queuedCount = 0;

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

                $queuedCount++;

                /*
                 * Paused campaigns retain QUEUED messages.
                 * ResumeCampaignAction will dispatch them later.
                 */
                if ($lockedCampaign->status === CampaignStatus::PAUSED) {
                    continue;
                }

                ProcessMessageJob::dispatch($message->fresh())
                    ->onQueue('messages')
                    ->afterCommit();
            }

            return $queuedCount;
        }, 3);
    }
}
