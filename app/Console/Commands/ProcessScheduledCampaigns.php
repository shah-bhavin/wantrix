<?php

namespace App\Console\Commands;

use App\Actions\Campaigns\DispatchCampaignAction;
use App\Enums\CampaignStatus;
use App\Models\Campaign;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-scheduled-campaigns')]
#[Description('Process campaigns whose scheduled time has arrived.')]
class ProcessScheduledCampaigns extends Command
{
    public function handle(
        DispatchCampaignAction $dispatchCampaign
    ): int {
        $campaigns = Campaign::query()
            ->where('status', CampaignStatus::SCHEDULED)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {

            $this->info('No scheduled campaigns are ready.');

            return self::SUCCESS;
        }

        foreach ($campaigns as $campaign) {

            $started = $dispatchCampaign->execute($campaign);

            if ($started) {

                $this->info(
                    "Campaign #{$campaign->id} started."
                );

            } else {

                $this->line(
                    "Campaign #{$campaign->id} was already processed."
                );
            }
        }

        return self::SUCCESS;
    }
}