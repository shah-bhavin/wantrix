<?php

namespace App\Actions\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Events\CampaignResumed;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;

class ResumeCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->refresh();

        if (! $campaign->status->canResume()) {
            return;
        }

        $updated = $campaign->newQuery()
            ->whereKey($campaign->id)
            ->where('status', CampaignStatus::PAUSED)
            ->update([
                'status' => CampaignStatus::PROCESSING,
                'completed_at' => null,
            ]);

        if ($updated !== 1) {
            return;
        }

        $campaign->refresh();

        event(new CampaignResumed($campaign));

        $delay = 0;

        $campaign
            ->messages()
            ->whereIn('status', [
                MessageStatus::QUEUED,
                MessageStatus::PENDING,
            ])
            ->chunkById(500, function ($messages) use ($campaign, &$delay) {

                foreach ($messages as $message) {

                    ProcessMessageJob::dispatch($message)
                        ->delay(
                            now()->addSeconds($delay)
                        );

                    $delay += $campaign->message_delay_seconds;
                }
            });
    }
}