<?php

namespace App\Livewire\Vendor;

use App\Models\Conversation;
use App\Models\WhatsappAccount;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ConversationList extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedConversationId = null;

    public ?int $selectedWhatsappAccountId = null;

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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedWhatsappAccountId(): void
    {
        $this->resetPage();

        /*
        |--------------------------------------------------------------------------
        | Clear selected conversation when account changes
        |--------------------------------------------------------------------------
        */

        $this->selectedConversationId = null;

        $this->dispatch(
            'conversation-account-changed',
            accountId: $this->selectedWhatsappAccountId
        );
    }

    public function selectConversation(int $conversationId): void
    {
        $conversation = Conversation::query()
            ->where('vendor_id', auth()->user()->vendor_id)
            ->when(
                $this->selectedWhatsappAccountId !== null,
                fn ($query) => $query->where(
                    'whatsapp_account_id',
                    $this->selectedWhatsappAccountId
                )
            )
            ->whereKey($conversationId)
            ->first();

        if (! $conversation) {
            return;
        }

        $this->selectedConversationId = $conversationId;

        $this->dispatch(
            'conversation-selected',
            id: $conversationId
        );
    }

    public function render()
    {
        $vendorId = auth()->user()->vendor_id;

        $accounts = WhatsappAccount::query()
            ->where('vendor_id', $vendorId)
            ->orderBy('name')
            ->get();

        $conversations = Conversation::query()
            ->where('vendor_id', $vendorId)

            ->with([
                'contact',
                'whatsappAccount',
            ])

            ->when(
                $this->selectedWhatsappAccountId !== null,
                fn ($query) => $query->where(
                    'whatsapp_account_id',
                    $this->selectedWhatsappAccountId
                )
            )

            ->when($this->search, function ($query) {
                $query->whereHas('contact', function ($contact) {
                    $contact
                        ->where(
                            'name',
                            'like',
                            "%{$this->search}%"
                        )
                        ->orWhere(
                            'phone_number',
                            'like',
                            "%{$this->search}%"
                        );
                });
            })

            ->orderByDesc('last_message_at')

            ->paginate(25);

        return view(
            'livewire.vendor.conversation-list',
            compact(
                'conversations',
                'accounts'
            )
        );
    }
}