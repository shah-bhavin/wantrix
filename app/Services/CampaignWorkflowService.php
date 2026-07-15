<?php

namespace App\Services;

use App\Actions\Campaigns\GenerateCampaignMessagesAction;
use App\Enums\CampaignStatus;
use App\Events\CampaignCompleted;
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
        //
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
        if (! $campaign->isReadyToComplete()) {
            return;
        }

        $updated = $campaign->newQuery()
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
}
