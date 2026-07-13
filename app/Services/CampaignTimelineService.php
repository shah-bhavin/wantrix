<?php

namespace App\Services;

use App\Models\Campaign;

class CampaignTimelineService
{
    public function get(Campaign $campaign): array
    {
        $timeline = [];

        /*
        |--------------------------------------------------------------------------
        | Campaign Created
        |--------------------------------------------------------------------------
        */
        $timeline[] = [
            'title' => 'Campaign Created',
            'description' => 'Campaign was created.',
            'time' => $campaign->created_at,
            'icon' => 'plus-circle',
            'color' => 'blue',
        ];

        /*
        |--------------------------------------------------------------------------
        | Messages Generated
        |--------------------------------------------------------------------------
        */
        if ($campaign->messages()->exists()) {
            $timeline[] = [
                'title' => 'Messages Generated',
                'description' => $campaign->messages()->count().' messages generated.',
                'time' => $campaign->messages()->oldest()->first()->created_at,
                'icon' => 'chat-bubble-left-right',
                'color' => 'amber',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Started
        |--------------------------------------------------------------------------
        */
        if ($campaign->started_at) {
            $timeline[] = [
                'title' => 'Campaign Started',
                'description' => 'Campaign processing started.',
                'time' => $campaign->started_at,
                'icon' => 'play',
                'color' => 'green',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Campaign Completed
        |--------------------------------------------------------------------------
        */
        if ($campaign->completed_at) {
            $timeline[] = [
                'title' => 'Campaign Completed',
                'description' => 'Campaign finished.',
                'time' => $campaign->completed_at,
                'icon' => 'check-circle',
                'color' => 'emerald',
            ];
        }

        return collect($timeline)
            ->sortByDesc('time')
            ->values()
            ->all();
    }
}
