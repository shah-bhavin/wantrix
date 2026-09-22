<div wire:poll.3s="refreshConversation" class="h-full flex flex-col relative"> @if(!$conversation)

    {{-- No conversation selected --}}
    <div class="flex-1 min-h-0 flex items-center justify-center">
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
    <div class="flex flex-col h-full min-h-0 overflow-hidden">

        {{-- ========================================================= --}}
        {{-- Conversation Header --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 border-b px-6 py-3 bg-white flex items-center justify-between">

            {{-- Customer --}}
            <div class="flex items-center gap-3 min-w-0">

                {{-- Avatar --}}
                <div class="
                w-11
                h-11
                shrink-0
                rounded-full
                bg-green-100
                text-green-700
                flex
                items-center
                justify-center
                font-semibold
                text-lg
            ">
                    {{ strtoupper(substr($conversation->contact->name ?? '?', 0, 1)) }}
                </div>

                {{-- Customer Details --}}
                <div class="min-w-0">

                    <div class="font-semibold text-slate-900 truncate">
                        {{ $conversation->contact->name }}
                    </div>

                    <div class="text-sm text-slate-500 truncate">
                        {{ $conversation->contact->phone_number }}
                    </div>

                </div>

            </div>


            {{-- Header Actions --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Customer Info --}}
                <button type="button" wire:click="openCustomerProfile" class="
                w-10
                h-10
                rounded-xl
                flex
                items-center
                justify-center
                text-slate-500
                hover:bg-slate-100
                hover:text-slate-700
                transition
            " title="Customer information">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>

                </button>


                {{-- Temporary Test Buttons --}}
                <button type="button" wire:click="createTestMessage" wire:loading.attr="disabled" class="
                px-3
                py-2
                bg-amber-500
                hover:bg-amber-600
                text-white
                rounded-xl
                text-sm
                disabled:opacity-50
            ">
                    <span wire:loading.remove wire:target="createTestMessage">
                        Test Incoming
                    </span>

                    <span wire:loading wire:target="createTestMessage">
                        Adding...
                    </span>
                </button>


                <button type="button" wire:click="createTestOutgoingMessage" wire:loading.attr="disabled" class="
                px-3
                py-2
                bg-blue-500
                hover:bg-blue-600
                text-white
                rounded-xl
                text-sm
                disabled:opacity-50
            ">
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

        {{-- ========================================================= --}}
        {{-- CUSTOMER PROFILE DRAWER --}}
        {{-- ========================================================= --}}

        @if($showCustomerProfile)

        <div class="
            absolute
            top-0
            right-0
            bottom-0
            w-[360px]
            max-w-full
            bg-white
            border-l
            shadow-xl
            z-30
            flex
            flex-col
        ">

            {{-- Drawer Header --}}
            <div class="shrink-0 border-b px-5 py-4 flex items-center justify-between">

                <div class="font-semibold text-lg">
                    Customer Details
                </div>

                <button type="button" wire:click="closeCustomerProfile" class="
                    w-9
                    h-9
                    rounded-xl
                    flex
                    items-center
                    justify-center
                    text-slate-500
                    hover:bg-slate-100
                    hover:text-slate-700
                " title="Close">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>


            {{-- Drawer Content --}}
            <div class="flex-1 min-h-0 overflow-y-auto p-5">

                {{-- Profile --}}
                <div class="text-center pb-6 border-b">

                    <div class="
                        mx-auto
                        w-20
                        h-20
                        rounded-full
                        bg-green-100
                        text-green-700
                        flex
                        items-center
                        justify-center
                        text-2xl
                        font-bold
                    ">
                        {{ strtoupper(substr($conversation->contact->name ?? '?', 0, 1)) }}
                    </div>

                    <div class="mt-3 font-semibold text-xl">
                        {{ $conversation->contact->name }}
                    </div>

                    <div class="text-sm text-slate-500 mt-1">
                        {{ $conversation->contact->phone_number }}
                    </div>

                </div>


                {{-- Contact Information --}}
                <div class="py-5 border-b">

                    <h3 class="text-sm font-semibold text-slate-900 mb-4">
                        Contact Information
                    </h3>

                    <div class="space-y-4">

                        {{-- Phone --}}
                        <div>

                            <div class="text-xs text-slate-400">
                                Phone
                            </div>

                            <div class="text-sm text-slate-700 mt-1">
                                {{ $conversation->contact->phone_number ?: 'Not available' }}
                            </div>

                        </div>


                        {{-- Email --}}
                        <div>

                            <div class="text-xs text-slate-400">
                                Email
                            </div>

                            <div class="text-sm text-slate-700 mt-1 break-all">
                                {{ $conversation->contact->email ?: 'Not available' }}
                            </div>

                        </div>


                        {{-- Company --}}
                        <div>

                            <div class="text-xs text-slate-400">
                                Company
                            </div>

                            <div class="text-sm text-slate-700 mt-1">
                                {{ $conversation->contact->company ?: 'Not available' }}
                            </div>

                        </div>


                        {{-- Location --}}
                        <div>

                            <div class="text-xs text-slate-400">
                                Location
                            </div>

                            <div class="text-sm text-slate-700 mt-1">

                                @php
                                $location = collect([
                                $conversation->contact->city,
                                $conversation->contact->state,
                                $conversation->contact->country,
                                ])->filter()->implode(', ');
                                @endphp

                                {{ $location ?: 'Not available' }}

                            </div>

                        </div>


                        {{-- Status --}}
                        <div>

                            <div class="text-xs text-slate-400">
                                Status
                            </div>

                            <div class="mt-1">

                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ ucfirst($conversation->contact->status ?? 'active') }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Tags --}}
                <div class="py-5 border-b">

                    <h3 class="text-sm font-semibold text-slate-900 mb-3">
                        Tags
                    </h3>

                    @if($conversation->contact->tags->count())

                    <div class="flex flex-wrap gap-2">

                        @foreach($conversation->contact->tags as $tag)

                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                            {{ $tag->name }}
                        </span>

                        @endforeach

                    </div>

                    @else

                    <div class="text-sm text-slate-400">
                        No tags assigned.
                    </div>

                    @endif

                </div>


                {{-- Groups --}}
                <div class="py-5 border-b">

                    <h3 class="text-sm font-semibold text-slate-900 mb-3">
                        Groups
                    </h3>

                    @if($conversation->contact->groups->count())

                    <div class="flex flex-wrap gap-2">

                        @foreach($conversation->contact->groups as $group)

                        <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-medium">
                            {{ $group->name }}
                        </span>

                        @endforeach

                    </div>

                    @else

                    <div class="text-sm text-slate-400">
                        No groups assigned.
                    </div>

                    @endif

                </div>


                {{-- Notes --}}
                <div class="py-5">

                    <h3 class="text-sm font-semibold text-slate-900 mb-3">
                        Notes
                    </h3>

                    @if($conversation->contact->notes)

                    <div class="text-sm text-slate-600 whitespace-pre-line">
                        {{ $conversation->contact->notes }}
                    </div>

                    @else

                    <div class="text-sm text-slate-400">
                        No notes available.
                    </div>

                    @endif

                </div>

            </div>

        </div>

        @endif



        <div id="chat-messages" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-6 space-y-4 bg-slate-50">

            @forelse($chatMessages as $message)

            <div wire:key="chat-message-{{ $message['direction'] }}-{{ $message['id'] }}" class="flex {{ $message['direction'] === 'outgoing'
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

                        @if($message['direction'] === 'outgoing')
                        <div class="flex items-center justify-end gap-1 mt-1 text-xs opacity-80">

                            @php
                            $status = $message['status'];

                            if ($status instanceof \App\Enums\MessageStatus) {
                            $status = $status->value;
                            }
                            @endphp

                            @php
                            $messageStatus = $message['status'];

                            if ($messageStatus instanceof \App\Enums\MessageStatus) {
                            $messageStatusEnum = $messageStatus;
                            } else {
                            $messageStatusEnum = \App\Enums\MessageStatus::tryFrom($messageStatus);
                            }
                            @endphp

                            @if($messageStatusEnum)
                            <div class="flex items-center justify-end gap-1 mt-1 text-xs opacity-80"
                                title="{{ $messageStatusEnum->label() }}">
                                <span class="{{ $messageStatusEnum->iconColor() }} font-semibold">
                                    {{ $messageStatusEnum->icon() }}
                                </span>

                                @if($messageStatusEnum === \App\Enums\MessageStatus::FAILED)
                                <span class="text-red-200">
                                    {{ $message['failure_reason'] ?? 'Message failed' }}
                                </span>
                                @endif
                            </div>
                            @endif

                        </div>
                        @endif

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