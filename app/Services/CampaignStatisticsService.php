<?php

namespace App\Services;

use App\Enums\MessageStatus;
use App\Models\Campaign;

class CampaignStatisticsService
{
    public function get(Campaign $campaign): array
    {
        $counts = $campaign->messages()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Total Messages
        $total = $counts->sum();

        // Individual Counts
        $pending = $counts[MessageStatus::PENDING->value] ?? 0;
        $queued = $counts[MessageStatus::QUEUED->value] ?? 0;
        $sending = $counts[MessageStatus::SENDING->value] ?? 0;
        $sent = $counts[MessageStatus::SENT->value] ?? 0;
        $delivered = $counts[MessageStatus::DELIVERED->value] ?? 0;
        $read = $counts[MessageStatus::READ->value] ?? 0;
        $failed = $counts[MessageStatus::FAILED->value] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Campaign Progress
        |--------------------------------------------------------------------------
        |
        | A campaign is considered finished when every message reaches
        | its final state.
        |
        | For now we consider:
        | READ + FAILED
        |
        | Later, when Meta Webhooks are integrated, READ will become
        | the final successful state.
        |
        */

        $completed = $read + $failed;

        return [

            'total' => $total,

            'pending' => $pending,

            'queued' => $queued,

            'sending' => $sending,

            'sent' => $sent,

            'delivered' => $delivered,

            'read' => $read,

            'failed' => $failed,

            // Overall campaign completion %
            'progress' => $total > 0
                ? round(($completed / $total) * 100)
                : 0,

            // How many messages were successfully sent?
            'success_rate' => $total > 0
                ? round(($sent / $total) * 100)
                : 0,

            // Of sent messages, how many got delivered?
            'delivery_rate' => $sent > 0
                ? round(($delivered / $sent) * 100)
                : 0,

            // Of delivered messages, how many were read?
            'read_rate' => $delivered > 0
                ? round(($read / $delivered) * 100)
                : 0,
        ];
    }
}