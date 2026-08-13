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

    public ?string $lastLoadedMessageAt = null;

    /*
    |--------------------------------------------------------------------------
    | Currently selected WhatsApp account
    |--------------------------------------------------------------------------
    */

    public ?int $selectedWhatsappAccountId = null;


    /*
    |--------------------------------------------------------------------------
    | Conversation Selected
    |--------------------------------------------------------------------------
    */

    #[On('conversation-selected')]
    public function loadConversation(int $id): void
    {
        $vendorId = auth()->user()->vendor_id;

        /*
        |--------------------------------------------------------------------------
        | Load conversation safely
        |--------------------------------------------------------------------------
        |
        | The conversation must belong to the authenticated vendor.
        |
        | If a WhatsApp account is currently selected, the conversation
        | must also belong to that WhatsApp account.
        |
        */

        $conversation = Conversation::query()
            ->where('vendor_id', $vendorId)

            ->when(
                $this->selectedWhatsappAccountId !== null,
                fn ($query) => $query->where(
                    'whatsapp_account_id',
                    $this->selectedWhatsappAccountId
                )
            )

            ->with('contact')

            ->find($id);

        /*
        |--------------------------------------------------------------------------
        | Invalid / inaccessible conversation
        |--------------------------------------------------------------------------
        */

        if (! $conversation) {
            $this->conversation = null;
            $this->chatMessages = [];
            $this->lastLoadedMessageAt = null;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Store conversation
        |--------------------------------------------------------------------------
        */

        $this->conversation = $conversation;

        /*
        |--------------------------------------------------------------------------
        | Mark conversation as read
        |--------------------------------------------------------------------------
        */

        $this->markAsRead();

        /*
        |--------------------------------------------------------------------------
        | Load messages
        |--------------------------------------------------------------------------
        */

        $this->loadMessages();
    }


    /*
    |--------------------------------------------------------------------------
    | WhatsApp Account Changed
    |--------------------------------------------------------------------------
    */

    #[On('conversation-account-changed')]
    public function changeWhatsappAccount(?int $accountId = null): void
    {
        /*
        |--------------------------------------------------------------------------
        | Store selected account
        |--------------------------------------------------------------------------
        */

        $this->selectedWhatsappAccountId = $accountId;

        /*
        |--------------------------------------------------------------------------
        | Clear currently opened conversation
        |--------------------------------------------------------------------------
        */

        $this->conversation = null;

        $this->chatMessages = [];

        $this->messageText = '';

        $this->lastLoadedMessageAt = null;
    }


    /*
    |--------------------------------------------------------------------------
    | Load Messages
    |--------------------------------------------------------------------------
    */

    public function loadMessages(): void
    {
        if (! $this->conversation) {
            $this->chatMessages = [];

            $this->lastLoadedMessageAt = null;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Incoming Messages
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Outgoing Messages
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Merge & Sort
        |--------------------------------------------------------------------------
        */

        $this->chatMessages = $incomingMessages
            ->concat($outgoingMessages)
            ->sortBy('created_at')
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | Remember Latest Loaded Message
        |--------------------------------------------------------------------------
        */

        $latestMessageAt = collect($this->chatMessages)
            ->pluck('created_at')
            ->filter()
            ->max();

        if ($latestMessageAt) {

            $this->lastLoadedMessageAt =
                $latestMessageAt->format('Y-m-d H:i:s');

        }


        /*
        |--------------------------------------------------------------------------
        | Scroll Browser To Latest Message
        |--------------------------------------------------------------------------
        */

        $this->dispatch('chat-messages-updated');
    }


    /*
    |--------------------------------------------------------------------------
    | Local Test Incoming Message
    |--------------------------------------------------------------------------
    */

    public function createTestMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $conversation = $this->conversation->fresh([
            'contact',
        ]);

        if (! $conversation) {
            return;
        }

        $conversation->incomingMessages()->create([

            'vendor_id' =>
                $conversation->vendor_id,

            'contact_id' =>
                $conversation->contact_id,

            'whatsapp_account_id' =>
                $conversation->whatsapp_account_id,

            'provider_message_id' =>
                'test-' . uniqid(),

            'conversation_id' =>
                $conversation->id,

            'from_phone' =>
                $conversation->contact->phone_number,

            'body' =>
                'Hello! This is a local test WhatsApp message.',

            'type' =>
                'text',

            'payload' => [
                'test' => true,
                'source' => 'local',
            ],

            'received_at' =>
                now(),
        ]);


        $conversation->touchIncoming();


        /*
        |--------------------------------------------------------------------------
        | Test message should immediately appear as read
        |--------------------------------------------------------------------------
        */

        $conversation->update([
            'unread_count' => 0,
        ]);


        $this->conversation = $conversation->fresh([
            'contact',
        ]);


        $this->dispatch('conversation-read');


        $this->loadMessages();
    }


    /*
    |--------------------------------------------------------------------------
    | Local Test Outgoing Message
    |--------------------------------------------------------------------------
    */

    public function createTestOutgoingMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $conversation = $this->conversation->fresh([
            'contact',
        ]);

        if (! $conversation) {
            return;
        }

        $message = $conversation->outgoingMessages()->create([

            'vendor_id' =>
                $conversation->vendor_id,

            'campaign_id' =>
                null,

            'contact_id' =>
                $conversation->contact_id,

            'whatsapp_account_id' =>
                $conversation->whatsapp_account_id,

            'body' =>
                'Hello! This is a local test outgoing message.',

            'type' =>
                'text',

            'status' =>
                'sent',

            'provider_message_id' =>
                'local-test-' . uniqid(),

            'sent_at' =>
                now(),

            'retry_count' =>
                0,
        ]);


        $message->statusHistories()->create([

            'status' =>
                'sent',

            'changed_at' =>
                now(),
        ]);


        $conversation->touchOutgoing();


        $this->conversation = $conversation->fresh([
            'contact',
        ]);


        $this->loadMessages();
    }


    /*
    |--------------------------------------------------------------------------
    | Send Message
    |--------------------------------------------------------------------------
    */

    public function sendMessage(): void
    {
        if (! $this->conversation) {
            return;
        }

        $body = trim($this->messageText);

        if ($body === '') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Extra account safety check
        |--------------------------------------------------------------------------
        */

        if (
            $this->selectedWhatsappAccountId !== null
            &&
            $this->conversation->whatsapp_account_id
                !== $this->selectedWhatsappAccountId
        ) {
            return;
        }


        app(SendMessageAction::class)
            ->execute(
                $this->conversation,
                $body
            );


        $this->messageText = '';


        $this->conversation =
            $this->conversation->fresh([
                'contact',
            ]);


        $this->loadMessages();
    }


    /*
    |--------------------------------------------------------------------------
    | Mark Conversation As Read
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Refresh Conversation
    |--------------------------------------------------------------------------
    */

    public function refreshConversation(): void
    {
        if (! $this->conversation) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Conversation Still Belongs To Selected Account
        |--------------------------------------------------------------------------
        */

        if (
            $this->selectedWhatsappAccountId !== null
            &&
            $this->conversation->whatsapp_account_id
                !== $this->selectedWhatsappAccountId
        ) {
            $this->conversation = null;

            $this->chatMessages = [];

            $this->lastLoadedMessageAt = null;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Latest Incoming Message
        |--------------------------------------------------------------------------
        */

        $latestIncoming = $this->conversation
            ->incomingMessages()
            ->latest('received_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Get Latest Outgoing Message
        |--------------------------------------------------------------------------
        */

        $latestOutgoing = $this->conversation
            ->outgoingMessages()
            ->latest('sent_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Determine Latest Message Timestamp
        |--------------------------------------------------------------------------
        */

        $latestIncomingAt =
            $latestIncoming?->received_at;

        $latestOutgoingAt =
            $latestOutgoing?->sent_at;


        $latestMessageAt = collect([
            $latestIncomingAt,
            $latestOutgoingAt,
        ])
            ->filter()
            ->max();


        if (! $latestMessageAt) {
            return;
        }


        $latestMessageAtString =
            $latestMessageAt->format('Y-m-d H:i:s');


        /*
        |--------------------------------------------------------------------------
        | Compare With Actually Loaded Message
        |--------------------------------------------------------------------------
        */

        if (
            $this->lastLoadedMessageAt === null
            ||
            $latestMessageAtString !==
                $this->lastLoadedMessageAt
        ) {

            $this->conversation =
                $this->conversation->fresh([
                    'contact',
                ]);


            $this->loadMessages();


            $this->markAsRead();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.vendor.chat-window'
        );
    }
}