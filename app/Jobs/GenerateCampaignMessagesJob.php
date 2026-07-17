<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Events\Campaigns\CampaignMessagesGenerated;
use App\Models\Campaign;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Throwable;

class GenerateCampaignMessagesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign
    ) {
    }

    public function handle(): void
    {
        $campaign = $this->campaign->fresh([
            'group',
            'template',
        ]);

        $jobs = [];

        $campaign->group
            ->contacts()
            ->select('contacts.id')
            ->chunkById(500, function ($contacts) use (&$jobs, $campaign) {

                $jobs[] = new GenerateMessageChunkJob(
                    $campaign,
                    $contacts->pluck('id')->all()
                );
            });

        if (empty($jobs)) {
            $campaign->update([
                'messages_generated_at' => now(),
            ]);

            event(new CampaignMessagesGenerated($campaign));

            return;
        }

        Bus::batch($jobs)
            ->then(function (Batch $batch) use ($campaign) {

                $campaign->update([
                    'messages_generated_at' => now(),
                ]);

                event(new CampaignMessagesGenerated(
                    $campaign->fresh()
                ));
            })
            ->catch(function (Batch $batch, Throwable $e) use ($campaign) {

                report($e);
            })
            ->dispatch();
    }
}