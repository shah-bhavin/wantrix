<?php

namespace App\Console\Commands;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Services\CampaignDispatcherService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-scheduled-campaigns')]
#[Description('Process campaigns whose scheduled time has arrived')]
class ProcessScheduledCampaigns extends Command
{
    public function handle(
        CampaignDispatcherService $dispatcher
    ): int {
        
        $campaigns = Campaign::query()
            ->where('status', CampaignStatus::SCHEDULED)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {

            $this->info(
                'No scheduled campaigns are ready.'
            );

            return self::SUCCESS;
        }

        foreach ($campaigns as $campaign) {

            $this->info(
                "Starting campaign #{$campaign->id}: {$campaign->name}"
            );

            $dispatcher->dispatch($campaign);
        }

        $this->info(
            "{$campaigns->count()} scheduled campaign(s) processed."
        );

        return self::SUCCESS;
    }
}
