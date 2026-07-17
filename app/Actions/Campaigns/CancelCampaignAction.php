<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignCancelled;
use App\Models\Campaign;

class CancelCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->refresh();

        if (! $campaign->status->canCancel()) {
            return;
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
            return;
        }

        $campaign->refresh();

        $campaign->messages()
            ->whereIn('status', [
                MessageStatus::PENDING,
                MessageStatus::QUEUED,
                MessageStatus::SENDING,
            ])
            ->update([
                'status' => MessageStatus::FAILED,
                'failure_reason' => 'Campaign was cancelled before message was sent.',
            ]);

        event(new CampaignCancelled($campaign));
    }
}