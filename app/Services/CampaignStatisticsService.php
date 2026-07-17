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

        $total = $counts->sum();

        $pending = (int) ($counts[MessageStatus::PENDING->value] ?? 0);
        $queued = (int) ($counts[MessageStatus::QUEUED->value] ?? 0);
        $sending = (int) ($counts[MessageStatus::SENDING->value] ?? 0);
        $sent = (int) ($counts[MessageStatus::SENT->value] ?? 0);
        $delivered = (int) ($counts[MessageStatus::DELIVERED->value] ?? 0);
        $read = (int) ($counts[MessageStatus::READ->value] ?? 0);
        $failed = (int) ($counts[MessageStatus::FAILED->value] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Message Processing
        |--------------------------------------------------------------------------
        */

        $processed = $sent
            + $delivered
            + $read
            + $failed;

        $active = $pending
            + $queued
            + $sending;

        /*
        |--------------------------------------------------------------------------
        | Success Metrics
        |--------------------------------------------------------------------------
        */

        $successful = $sent
            + $delivered
            + $read;

        return [

            /*
            |--------------------------------------------------------------------------
            | Counts
            |--------------------------------------------------------------------------
            */

            'total' => $total,

            'pending' => $pending,

            'queued' => $queued,

            'sending' => $sending,

            'sent' => $sent,

            'delivered' => $delivered,

            'read' => $read,

            'failed' => $failed,

            'processed' => $processed,

            'active' => $active,

            'successful' => $successful,

            /*
            |--------------------------------------------------------------------------
            | Campaign Progress
            |--------------------------------------------------------------------------
            |
            | A message is processed once it reaches a final state.
            |
            | SENT, DELIVERED, READ and FAILED are all final states
            | for the current system.
            |
            */

            'progress' => $total > 0
                ? round(($processed / $total) * 100)
                : 0,

            /*
            |--------------------------------------------------------------------------
            | Success Rate
            |--------------------------------------------------------------------------
            |
            | Successfully processed messages compared to total messages.
            |
            */

            'success_rate' => $total > 0
                ? round(($successful / $total) * 100)
                : 0,

            /*
            |--------------------------------------------------------------------------
            | Failure Rate
            |--------------------------------------------------------------------------
            */

            'failure_rate' => $total > 0
                ? round(($failed / $total) * 100)
                : 0,

            /*
            |--------------------------------------------------------------------------
            | Delivery Rate
            |--------------------------------------------------------------------------
            |
            | Sent messages that were delivered.
            |
            */

            'delivery_rate' => $sent > 0
                ? round(($delivered / $sent) * 100)
                : 0,

            /*
            |--------------------------------------------------------------------------
            | Read Rate
            |--------------------------------------------------------------------------
            |
            | Delivered messages that were read.
            |
            */

            'read_rate' => $delivered > 0
                ? round(($read / $delivered) * 100)
                : 0,
        ];
    }
}