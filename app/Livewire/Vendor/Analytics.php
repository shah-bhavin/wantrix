<?php

namespace App\Livewire\Vendor;

use App\Models\Campaign;
use App\Models\Message;
use Livewire\Component;

class Analytics extends Component
{
    public function render()
    {
        $vendorId = auth()->user()->vendor_id;

        $totalMessages = Message::where('vendor_id', $vendorId)->count();
        $pendingMessages = Message::where('vendor_id', $vendorId)->where('status', 'pending')->count();
        $sentMessages = Message::where('vendor_id', $vendorId)->where('status', 'sent')->count();
        $failedMessages = Message::where('vendor_id', $vendorId)->where('status', 'failed')->count();

        $recentCampaigns = Campaign::where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.vendor.analytics', compact(
            'totalMessages',
            'pendingMessages',
            'sentMessages',
            'failedMessages',
            'recentCampaigns'
        ))->layout('layouts.vendor');
    }
}
