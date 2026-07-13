<?php

namespace App\Services;

use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class CampaignDispatcherService
{
    public function dispatch(Campaign $campaign): void
    {
        DB::transaction(function () use ($campaign) {

            $campaign->update([
                'status' => CampaignStatus::PROCESSING,
                'started_at' => now(),
            ]);
            
        });
        
        $campaign
            ->messages()
            ->chunkById(500, function ($messages) {
                $ids = $messages->modelKeys();

                Message::whereIn('id', $ids)
                    ->update([
                        'status' => MessageStatus::QUEUED,
                    ]);

                foreach ($messages as $message) {

                    $message->status = MessageStatus::QUEUED;

                    ProcessMessageJob::dispatch($message);

                }
            });
    }
}