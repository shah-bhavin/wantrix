<div
    wire:poll.2s="refreshConversation"
    class="h-full flex flex-col"
>
    @if(!$conversation)

    {{-- No conversation selected --}}
    <div class="flex-1 min-h-0 flex items-center justify-center" wire:poll.2s>
        <div class="text-center">

            <svg class="mx-auto w-20 h-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2h-4" />

            </svg>

            <h2 class="text-xl font-semibold mt-4">
                Select a conversation
            </h2>

            <p class="text-slate-500 mt-2">
                Choose a customer to start chatting.
            </p>

        </div>
    </div>

    @else

    {{-- Entire Chat Window --}}
    <div class="flex flex-col h-full min-h-0 overflow-hidden" wire:poll.2s="refreshConversation">

        {{-- ========================================================= --}}
        {{-- Conversation Header --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 border-b px-6 py-4 bg-white flex items-center justify-between">

            <div>

                <div class="font-semibold text-lg">
                    {{ $conversation->contact->name }}
                </div>

                <div class="text-sm text-slate-500">
                    {{ $conversation->contact->phone_number }}
                </div>

            </div>

            {{-- Temporary Test Buttons --}}
            <div class="flex items-center gap-2">

                <button type="button" wire:click="createTestMessage" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl disabled:opacity-50">

                    <span wire:loading.remove wire:target="createTestMessage">
                        Test Incoming
                    </span>

                    <span wire:loading wire:target="createTestMessage">
                        Adding...
                    </span>

                </button>

                <button type="button" wire:click="createTestOutgoingMessage" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl disabled:opacity-50">

                    <span wire:loading.remove wire:target="createTestOutgoingMessage">
                        Test Outgoing
                    </span>

                    <span wire:loading wire:target="createTestOutgoingMessage">
                        Adding...
                    </span>

                </button>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SCROLLABLE MESSAGE AREA --}}
        {{-- ========================================================= --}}

        <div id="chat-messages" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-6 space-y-4 bg-slate-50">

            @forelse($chatMessages as $message)

            <div wire:key="chat-message-{{ $message['id'] }}" class="flex {{ $message['direction'] === 'outgoing'
                            ? 'justify-end'
                            : 'justify-start' }}">

                <div class="max-w-lg">

                    <div class="
                                rounded-2xl
                                px-4
                                py-3
                                shadow-sm
                                break-words
                                {{ $message['direction'] === 'outgoing'
                                    ? 'bg-green-500 text-white'
                                    : 'bg-white border border-slate-200 text-slate-800'
                                }}
                            ">

                        {{-- TEXT --}}
                        @if(
                        $message['type'] instanceof \App\Enums\MessageType
                        ? $message['type']->value === 'text'
                        : $message['type'] === 'text'
                        )

                        <div>
                            {{ $message['body'] }}
                        </div>

                        @endif


                        {{-- IMAGE --}}
                        @if(
                        $message['type'] instanceof \App\Enums\MessageType
                        ? $message['type']->value === 'image'
                        : $message['type'] === 'image'
                        )

                        @if($message['media'])

                        <img src="{{ $message['media']->url }}" alt="{{ $message['media']->original_name }}"
                            class="rounded-xl max-w-xs">

                        @endif

                        @if($message['body'])

                        <div class="mt-2">
                            {{ $message['body'] }}
                        </div>

                        @endif

                        @endif


                        {{-- DOCUMENT --}}
                        @if(
                        $message['type'] instanceof \App\Enums\MessageType
                        ? $message['type']->value === 'document'
                        : $message['type'] === 'document'
                        )

                        <div class="flex items-center gap-3">

                            <div class="text-2xl">
                                📄
                            </div>

                            <div>
                                {{ $message['media']?->original_name ?? 'Document' }}
                            </div>

                        </div>

                        @endif


                        {{-- VIDEO --}}
                        @if(
                        $message['type'] instanceof \App\Enums\MessageType
                        ? $message['type']->value === 'video'
                        : $message['type'] === 'video'
                        )

                        @if($message['media'])

                        <video controls class="rounded-xl max-w-xs">

                            <source src="{{ $message['media']->url }}">

                        </video>

                        @endif

                        @endif


                        {{-- AUDIO --}}
                        @if(
                        $message['type'] instanceof \App\Enums\MessageType
                        ? $message['type']->value === 'audio'
                        : $message['type'] === 'audio'
                        )

                        @if($message['media'])

                        <audio controls class="max-w-xs">

                            <source src="{{ $message['media']->url }}">

                        </audio>

                        @endif

                        @endif


                        {{-- Timestamp --}}
                        <div class="flex justify-end mt-2 text-xs opacity-70">

                            {{ $message['created_at']?->format('h:i A') }}

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="h-full flex items-center justify-center">

                <div class="text-center text-slate-400">

                    <div class="text-4xl mb-3">
                        💬
                    </div>

                    <div>
                        No messages yet.
                    </div>

                </div>

            </div>

            @endforelse

            {{-- Invisible bottom anchor --}}
            <div id="chat-messages-bottom"></div>

        </div>


        {{-- ========================================================= --}}
        {{-- MESSAGE COMPOSER --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 border-t bg-white px-4 py-3">

            <form wire:submit="sendMessage" class="flex items-end gap-3">

                <div class="flex-1">

                    <textarea wire:model="messageText" rows="1" placeholder="Type a message..." class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3
                                focus:border-green-500 focus:ring-green-500"></textarea>

                </div>

                <button type="submit" wire:loading.attr="disabled" class="px-5 py-3 rounded-xl bg-green-500 text-white font-medium
                            hover:bg-green-600 disabled:opacity-50">

                    <span wire:loading.remove wire:target="sendMessage">
                        Send
                    </span>

                    <span wire:loading wire:target="sendMessage">
                        Sending...
                    </span>

                </button>

            </form>

        </div>

    </div>

    @endif


    {{-- ========================================================= --}}
    {{-- AUTO SCROLL --}}
    {{-- ========================================================= --}}

    <script>
        document.addEventListener('livewire:init', () => {

            const scrollToBottom = () => {

                const container = document.getElementById('chat-messages');

                if (!container) {
                    return;
                }

                requestAnimationFrame(() => {

                    container.scrollTop = container.scrollHeight;

                });

            };


            // Initial load
            scrollToBottom();


            // After Livewire updates the message list
            Livewire.on('chat-messages-updated', () => {

                setTimeout(() => {
                    scrollToBottom();
                }, 50);

            });


            // Also handle Livewire DOM updates
            Livewire.hook('morph.updated', ({ el }) => {

                if (
                    el.id === 'chat-messages' ||
                    el.querySelector?.('#chat-messages')
                ) {

                    setTimeout(() => {
                        scrollToBottom();
                    }, 50);

                }

            });

        });
    </script>
</div>