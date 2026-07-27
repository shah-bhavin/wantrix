<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Models\Campaign;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMessageChunkJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
        public Campaign $campaign,
        public array $contactIds,
    ) {
        $this->onQueue('campaign-generation');
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $contacts = $this->campaign
            ->group
            ->contacts()
            ->whereIn('contacts.id', $this->contactIds)
            ->get();

        foreach ($contacts as $contact) {
            $message = $this->campaign
                ->messages()
                ->create([
                    'vendor_id' => $this->campaign->vendor_id,
                    'contact_id' => $contact->id,
                    'body' => $this->campaign->template->body,
                    'status' => MessageStatus::PENDING,
                ]);

            $message->statusHistories()->create([
                'status' => MessageStatus::PENDING,
                'changed_at' => now(),
            ]);
        }
    }
}
