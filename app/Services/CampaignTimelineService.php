<?php

namespace App\Services;

use App\Models\Campaign;

class CampaignTimelineService
{
    public function get(Campaign $campaign): array
    {
        return $campaign->activities()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($activity) {
                return [
                    'title' => $activity->type->label(),
                    'description' => $activity->description,
                    'time' => $activity->created_at,
                    'icon' => $this->icon($activity->type),
                    'color' => $this->color($activity->type),
                ];
            })
            ->values()
            ->all();
    }

    private function icon($type): string
    {
        return match ($type->value) {
            'created' => 'plus-circle',
            'messages_generated' => 'chat-bubble-left-right',
            'started' => 'play',
            'paused' => 'pause',
            'resumed' => 'play',
            'completed' => 'check-circle',
            'cancelled' => 'x-circle',
            default => 'information-circle',
        };
    }

    private function color($type): string
    {
        return match ($type->value) {
            'created' => 'blue',
            'messages_generated' => 'amber',
            'started' => 'green',
            'paused' => 'amber',
            'resumed' => 'green',
            'completed' => 'emerald',
            'cancelled' => 'red',
            default => 'blue',
        };
    }
}
