<?php

namespace App\Livewire\Vendor;

use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class MessageDrawer extends Component
{
    public ?Message $message = null;

    public bool $open = false;

    #[On('show-message')]
    public function show(int $id): void
    {
        $this->message = Message::with([
            'contact',
            'campaign.group',
            'campaign.template',
        ])
        ->where('vendor_id', auth()->user()->vendor_id)
        ->findOrFail($id);

        $this->open = true;
    }

    public function close(): void
    {
        $this->reset('open', 'message');
    }

    public function render()
    {
        return view('livewire.vendor.message-drawer');
    }
}