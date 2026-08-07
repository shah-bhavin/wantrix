<?php

namespace App\Livewire\Vendor;

use App\Actions\Messages\SendMessageAction;
use App\Models\Conversation;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatWindow extends Component
{
    public ?Conversation $conversation = null;

    public array $chatMessages = [];
    public string $messageText = '';

    #[On('conversation-selected')]
    public function loadConversation(int $id): void
    {
        $this->conversation = Conversation::with([
            'contact',
        ])->find($id);

        if (! $this->conversation) {
            return;
        }

        $this->markAsRead();

        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (! $this->conversation) {
            $this->chatMessages = [];

            return;
        }

        $incomingMessages = $this->conversation
            ->incomingMessages()
            ->with([
                'contact',
                'media',
            ])
            ->get()
            ->map(function ($message) {

                return [
                    'id' => $message->id,

                    'direction' => 'incoming',

                    'type' => $message->type,

                    'body' => $message->body,

                    'media' => $message->media,

                    'created_at' => $message->received_at
                        ?? $message->created_at,
                ];
            });

        $outgoingMessages = $this->conversation
            ->outgoingMessages()
            ->with([
                'contact',
                'media',
                'whatsappTemplate',
            ])
            ->get()
            ->map(function ($message) {

                return [
                    'id' => $message->id,

                    'direction' => 'outgoing',

                    'type' => $message->type,

                    'body' => $message->body,

                    'media' => $message->media,

                    'created_at' => $message->sent_at
                        ?? $message->created_at,
                ];
            });

        $this->chatMessages = $incomingMessages
            ->concat($outgoingMessages)
            ->sortBy('created_at')
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Tell the browser to scroll to the latest message
        |--------------------------------------------------------------------------
        */

        $this->dispatch('chat-messages-updated');
    }

    public function createTestMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $conversation = $this->conversation->fresh([
            'contact',
        ]);

        $conversation->incomingMessages()->create([
            'vendor_id' => $conversation->vendor_id,

            'contact_id' => $conversation->contact_id,

            'whatsapp_account_id' => $conversation->whatsapp_account_id,

            'provider_message_id' => 'test-' . uniqid(),

            'conversation_id' => $conversation->id,

            'from_phone' => $conversation->contact->phone_number,

            'body' => 'Hello! This is a local test WhatsApp message.',

            'type' => 'text',

            'payload' => [
                'test' => true,
                'source' => 'local',
            ],

            'received_at' => now(),
        ]);

        // $conversation->touchIncoming();

        // $this->conversation = $conversation->fresh([
        //     'contact',
        // ]);

        $conversation->touchIncoming();

        $conversation->update([
            'unread_count' => 0,
        ]);

        $this->conversation = $conversation->fresh([
            'contact',
        ]);

        $this->dispatch('conversation-read');

        $this->loadMessages();
    }

    public function createTestOutgoingMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $conversation = $this->conversation->fresh([
            'contact',
        ]);

        $message = $conversation->outgoingMessages()->create([
            'vendor_id' => $conversation->vendor_id,
            'campaign_id' => null,
            'contact_id' => $conversation->contact_id,
            'whatsapp_account_id' => $conversation->whatsapp_account_id,

            'body' => 'Hello! This is a local test outgoing message.',

            'type' => 'text',

            'status' => 'sent',

            'provider_message_id' => 'local-test-' . uniqid(),

            'sent_at' => now(),

            'retry_count' => 0,
        ]);

        $message->statusHistories()->create([
            'status' => 'sent',
            'changed_at' => now(),
        ]);

        $conversation->touchOutgoing();

        $this->conversation = $conversation->fresh([
            'contact',
        ]);

        $this->loadMessages();
    }

    public function render()
    {
        return view(
            'livewire.vendor.chat-window'
        );
    }

    public function sendMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $body = trim($this->messageText);

        if ($body === '') {
            return;
        }

        app(SendMessageAction::class)
            ->execute(
                $this->conversation,
                $body
            );

        $this->messageText = '';

        $this->conversation = $this->conversation->fresh([
            'contact',
        ]);

        $this->loadMessages();
    }

    public function markAsRead(): void
    {
        if (! $this->conversation) {
            return;
        }

        if ($this->conversation->unread_count > 0) {
            $this->conversation->update([
                'unread_count' => 0,
            ]);

            $this->conversation->refresh();

            $this->dispatch('conversation-read');
        }
    }

    public function refreshConversation(): void
    {
        if (! $this->conversation) {
            return;
        }

        $latestIncoming = $this->conversation
            ->incomingMessages()
            ->latest('received_at')
            ->first();

        $latestOutgoing = $this->conversation
            ->outgoingMessages()
            ->latest('sent_at')
            ->first();

        $latestIncomingAt = $latestIncoming?->received_at;
        $latestOutgoingAt = $latestOutgoing?->sent_at;

        $latestMessageAt = collect([
            $latestIncomingAt,
            $latestOutgoingAt,
        ])
            ->filter()
            ->max();

        if (
            $latestMessageAt &&
            (
                ! $this->conversation->last_message_at ||
                $latestMessageAt->gt(
                    $this->conversation->last_message_at
                )
            )
        ) {
            $this->conversation = $this->conversation->fresh([
                'contact',
            ]);

            $this->loadMessages();

            $this->markAsRead();
        }
    }
}
