<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignCancelled;
use App\Models\Campaign;
use App\Services\MessageStatusService;


class CancelCampaignAction
{
    public function __construct(
        protected MessageStatusService $statusService
    ) {}
    
    public function execute(Campaign $campaign): bool
    {
        $campaign->refresh();

        if (! $campaign->status->canCancel()) {
            return false;
        }

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
            return false;
        }

        $campaign->refresh();

        $messages = $campaign
            ->messages()
            ->whereIn('status', [
                MessageStatus::PENDING,
                MessageStatus::QUEUED,
                MessageStatus::SENDING,
            ])
            ->get();

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

        event(new CampaignCancelled($campaign));
        return true;
    }
}
