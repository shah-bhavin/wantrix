<?php

namespace App\Jobs;

use App\Jobs\GenerateMessageChunkJob;
use App\Models\Campaign;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCampaignMessagesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign
    ) {}

    public function handle(): void
    {

        $campaign = $this->campaign->fresh([
            'group.contacts',
            'template',
        ]);

        $campaign->group
            ->contacts()
            ->select('contacts.id')
            ->chunkById(500, function ($contacts) use ($campaign) {

                GenerateMessageChunkJob::dispatch(
                    $campaign,
                    $contacts->pluck('id')->all()
                );
            });

        $campaign->update([
            'messages_generated_at' => now(),
        ]);
    }
}
