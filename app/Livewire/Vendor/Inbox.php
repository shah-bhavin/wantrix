<?php

namespace App\Livewire\Vendor;

use App\Models\Conversation;
use Livewire\Attributes\On;
use Livewire\Component;

class Inbox extends Component
{
    public ?Conversation $selectedConversation = null;

    public string $search = '';

    public function selectConversation($id): void
    {
        $this->selectedConversation = Conversation::find($id);
    }

    
    public function render()
    {
        $conversations = Conversation::query()

            ->where('vendor_id', auth()->user()->vendor_id)

            ->with([
                'contact',
            ])

            ->when($this->search, function ($q) {

                $q->whereHas('contact', function ($contact) {

                    $contact->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone_number', 'like', "%{$this->search}%");
                });
            })

            ->orderByDesc('last_message_at')

            ->paginate(25);

        return view(
            'livewire.vendor.inbox',
            compact('conversations')
        )->layout('layouts.vendor');
    }
}
