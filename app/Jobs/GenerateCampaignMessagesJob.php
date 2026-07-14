<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Models\Campaign;
use App\Models\Message;
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
        if ($this->campaign->messages_generated_at) {
            return;
        }

        $this->campaign->load('template');

        $this->campaign
            ->group
            ->contacts()
            ->chunkById(1000, function ($contacts) {

                $rows = [];

                foreach ($contacts as $contact) {

                    $rows[] = [

                        'vendor_id' => $this->campaign->vendor_id,

                        'campaign_id' => $this->campaign->id,

                        'contact_id' => $contact->id,

                        'body' => $this->campaign->template->body,

                        'status' => MessageStatus::PENDING,

                        'created_at' => now(),

                        'updated_at' => now(),

                    ];
                }

                Message::insert($rows);
            });
        $this->campaign->update([

            'messages_generated_at' => now(),

        ]);
    }
}
