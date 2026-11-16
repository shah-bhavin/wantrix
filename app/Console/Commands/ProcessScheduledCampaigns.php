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

        Campaign::query()
            ->where('status', CampaignStatus::SCHEDULED)
            ->whereNotNull('scheduled_at')
            ->whereNotNull('messages_generated_at')
            ->where('scheduled_at', '<=', now())
            ->chunkById(100, function ($campaigns) use (
                $dispatchCampaign
            ) {

                foreach ($campaigns as $campaign) {

                    $started = $dispatchCampaign
                        ->execute($campaign);

                    if ($started) {

                        $this->info(
                            "Campaign #{$campaign->id} started."
                        );

                    } else {

                        $this->line(
                            "Campaign #{$campaign->id} was not dispatched."
                        );
                    }
                }
            });

        return self::SUCCESS;
    }
}