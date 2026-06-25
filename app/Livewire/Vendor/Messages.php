<?php

namespace App\Livewire\Vendor;

use App\Models\Message;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;

    public function render()
    {
        $messages = Message::query()
            ->with(['campaign', 'contact'])
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->paginate(20);

        return view('livewire.vendor.messages', compact('messages'))->layout('layouts.vendor');
    }
}
