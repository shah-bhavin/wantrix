<?php

namespace App\Services;

use App\Models\User;
use App\Enums\MessageStatus;

class AnalyticsService
{
    public function getData(User $user): array
    {
        $vendor = $user->vendor;
        $messages = $vendor->messages();
        $totalMessages = $messages->count();

        $sent = $vendor->messages()->where('status', MessageStatus::SENT)->count();
        $delivered = $vendor->messages()->where('status', MessageStatus::DELIVERED)->count();
        $read = $vendor->messages()->where('status', MessageStatus::READ)->count();
        $failed = $vendor->messages()->where('status', MessageStatus::FAILED)->count();

        return [
            'campaigns' => $vendor->campaigns()->count(),
            'contacts' => $vendor->contacts()->count(),
            'messages' => $totalMessages,
            'sent' => $sent,
            'delivered' => $delivered,
            'read' => $read,
            'failed' => $failed,
            'delivery_rate' => $sent > 0 ? round(($delivered / $sent) * 100, 2) : 0,
            'read_rate' => $delivered > 0 ? round(($read / $delivered) * 100, 2) : 0,
        ];
    }
}
