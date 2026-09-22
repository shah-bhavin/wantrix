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

    /*
    |--------------------------------------------------------------------------
    | Conversation Read
    |--------------------------------------------------------------------------
    */

    #[On('conversation-read')]
    public function conversationRead(): void
    {
        // Event intentionally triggers a re-render.
    }

    /*
    |--------------------------------------------------------------------------
    | Refresh
    |--------------------------------------------------------------------------
    */

    public function refreshConversations(): void
    {
        // Polling triggers render().
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Account Changed
    |--------------------------------------------------------------------------
    */

    public function updatedSelectedWhatsappAccountId(): void
    {
        $this->resetPage();

        $this->selectedConversationId = null;

        $this->dispatch(
            'conversation-account-changed',
            accountId: $this->selectedWhatsappAccountId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Select Conversation
    |--------------------------------------------------------------------------
    */

    public function selectConversation(int $conversationId): void
    {
        $conversation = Conversation::query()
            ->where(
                'vendor_id',
                auth()->user()->vendor_id
            )

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

    /*
    |--------------------------------------------------------------------------
    | Conversation Preview
    |--------------------------------------------------------------------------
    */

    protected function conversationPreview(
        Conversation $conversation
    ): string {

        $latestIncoming = $conversation
            ->incomingMessages()
            ->latest('received_at')
            ->first();

        $latestOutgoing = $conversation
            ->outgoingMessages()
            ->latest('sent_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Determine latest message
        |--------------------------------------------------------------------------
        */

        $latestMessage = collect([
            $latestIncoming,
            $latestOutgoing,
        ])
            ->filter()
            ->sortByDesc(function ($message) {

                return $message->received_at
                    ?? $message->sent_at
                    ?? $message->created_at;
            })
            ->first();

        if (! $latestMessage) {
            return 'No messages yet';
        }

        $body = trim((string) $latestMessage->body);

        /*
        |--------------------------------------------------------------------------
        | Media Messages
        |--------------------------------------------------------------------------
        */

        if ($body === '') {

            return match (
                $latestMessage->type instanceof \App\Enums\MessageType
                    ? $latestMessage->type->value
                    : $latestMessage->type
            ) {

                'image' => '📷 Image',

                'video' => '🎥 Video',

                'audio' => '🎵 Audio',

                'document' => '📄 Document',

                default => 'Message',
            };
        }

        /*
        |--------------------------------------------------------------------------
        | Limit Preview Length
        |--------------------------------------------------------------------------
        */

        if (mb_strlen($body) > 45) {

            $body = mb_substr($body, 0, 45) . '...';
        }

        /*
        |--------------------------------------------------------------------------
        | Outgoing Prefix
        |--------------------------------------------------------------------------
        */

        if ($latestMessage->direction === 'outgoing') {

            return 'You: ' . $body;
        }

        return $body;
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $vendorId = auth()->user()->vendor_id;

        $accounts = WhatsappAccount::query()
            ->where(
                'vendor_id',
                $vendorId
            )
            ->orderBy('name')
            ->get();

        $conversations = Conversation::query()

            ->where(
                'vendor_id',
                $vendorId
            )

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

            ->when(
                $this->search,
                function ($query) {

                    $query->whereHas(
                        'contact',
                        function ($contact) {

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
                        }
                    );
                }
            )

            ->orderByDesc('last_message_at')

            ->paginate(25);

        /*
        |--------------------------------------------------------------------------
        | Attach Preview
        |--------------------------------------------------------------------------
        */

        $conversations->getCollection()->transform(
            function ($conversation) {

                $conversation->inbox_preview =
                    $this->conversationPreview(
                        $conversation
                    );

                return $conversation;
            }
        );

        return view(
            'livewire.vendor.conversation-list',
            compact(
                'conversations',
                'accounts'
            )
        );
    }
}