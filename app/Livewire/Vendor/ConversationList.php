<?php

namespace App\Livewire\Vendor;

use App\Models\Conversation;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ConversationList extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedConversationId = null;

    #[On('conversation-read')]
    public function conversationRead(): void
    {
        // Empty method is intentional.
        // Receiving the Livewire event causes this component to re-render.
    }

    public function refreshConversations(): void
    {
        // Intentionally empty.
        // Livewire polling causes render() to run again.
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversationId = $conversationId;

        $this->dispatch(
            'conversation-selected',
            id: $conversationId
        );
    }

    public function render()
    {
        $conversations = Conversation::query()

            ->where('vendor_id', auth()->user()->vendor_id)

            ->with([
                'contact',
            ])

            ->when($this->search, function ($query) {

                $query->whereHas('contact', function ($contact) {

                    $contact

                        ->where('name', 'like', "%{$this->search}%")

                        ->orWhere('phone_number', 'like', "%{$this->search}%");
                });
            })

            ->orderByDesc('last_message_at')

            ->paginate(25);

        return view(
            'livewire.vendor.conversation-list',
            compact('conversations')
        );
    }
}
