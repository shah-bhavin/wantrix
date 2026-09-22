<div class="h-full min-h-0 flex flex-col">

    <div
        wire:poll.3s="refreshConversations"
        class="flex flex-col flex-1 min-h-0"
    >

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 p-4 border-b bg-white">

            <div class="flex items-center justify-between mb-3">

                <h2 class="text-lg font-semibold text-slate-800">
                    Conversations
                </h2>

                <span class="text-xs text-slate-400">
                    {{ $conversations->total() }}
                </span>

            </div>


            {{-- WhatsApp Account --}}

            <label class="block text-xs font-medium text-slate-500 mb-1.5">
                WhatsApp Account
            </label>

            <select
                wire:model.live="selectedWhatsappAccountId"
                class="w-full rounded-xl border-slate-300 text-sm"
            >

                <option value="">
                    All WhatsApp Accounts
                </option>

                @foreach($accounts as $account)

                    <option value="{{ $account->id }}">

                        {{ $account->name }}

                        @if($account->display_phone_number)
                            — {{ $account->display_phone_number }}
                        @endif

                    </option>

                @endforeach

            </select>

        </div>


        {{-- ========================================================= --}}
        {{-- SEARCH --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 p-3 border-b bg-white">

            <div class="relative">

                <input
                    wire:model.live.debounce.500ms="search"
                    type="text"
                    placeholder="Search conversations..."
                    class="w-full rounded-xl border-slate-300
                           pl-10 pr-4 py-2.5 text-sm
                           focus:border-green-500
                           focus:ring-green-500"
                >

                <svg
                    class="absolute left-3 top-3 w-4 h-4 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                    />

                </svg>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CONVERSATIONS --}}
        {{-- ========================================================= --}}

        <div class="flex-1 min-h-0 overflow-y-auto">

            @forelse($conversations as $conversation)

                @php

                    $isSelected =
                        $selectedConversationId == $conversation->id;

                    $hasUnread =
                        $conversation->unread_count > 0;

                    $lastMessageAt =
                        $conversation->last_message_at;

                @endphp


                <button
                    type="button"

                    wire:key="conversation-{{ $conversation->id }}"

                    wire:click="selectConversation({{ $conversation->id }})"

                    @class([

                        'w-full text-left border-b p-4 transition duration-150',

                        'bg-green-50' => $isSelected,

                        'bg-white hover:bg-slate-50' =>
                            ! $isSelected,

                    ])
                >

                    <div class="flex gap-3">


                        {{-- ================================================= --}}
                        {{-- AVATAR --}}
                        {{-- ================================================= --}}

                        <div class="shrink-0">

                            <div
                                class="w-11 h-11 rounded-full
                                       flex items-center justify-center
                                       font-semibold text-sm
                                       bg-green-100 text-green-700"
                            >

                                {{ strtoupper(
                                    mb_substr(
                                        $conversation->contact->name ?: '?',
                                        0,
                                        1
                                    )
                                ) }}

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- CONTENT --}}
                        {{-- ================================================= --}}

                        <div class="min-w-0 flex-1">


                            {{-- Name + Time --}}

                            <div class="flex items-center justify-between gap-2">

                                <div
                                    @class([
                                        'truncate',
                                        'font-semibold text-slate-900' =>
                                            $hasUnread,
                                        'font-medium text-slate-700' =>
                                            ! $hasUnread,
                                    ])
                                >

                                    {{ $conversation->contact->name }}

                                </div>


                                @if($lastMessageAt)

                                    <div
                                        @class([
                                            'text-xs shrink-0',
                                            'text-green-600 font-medium' =>
                                                $hasUnread,
                                            'text-slate-400' =>
                                                ! $hasUnread,
                                        ])
                                    >

                                        @if($lastMessageAt->isToday())

                                            {{ $lastMessageAt->format('H:i') }}

                                        @elseif($lastMessageAt->isYesterday())

                                            Yesterday

                                        @else

                                            {{ $lastMessageAt->format('d/m/Y') }}

                                        @endif

                                    </div>

                                @endif

                            </div>


                            {{-- Phone --}}

                            <div class="text-xs text-slate-400 mt-0.5">

                                {{ $conversation->contact->phone_number }}

                            </div>


                            {{-- Last Message + Unread --}}

                            <div class="flex items-center justify-between gap-2 mt-1.5">


                                <div
                                    @class([
                                        'text-sm truncate',
                                        'font-medium text-slate-700' =>
                                            $hasUnread,
                                        'text-slate-500' =>
                                            ! $hasUnread,
                                    ])
                                >

                                    {{ $conversation->inbox_preview }}

                                </div>


                                @if($hasUnread)

                                    <span
                                        class="shrink-0
                                               min-w-5 h-5 px-1.5
                                               inline-flex items-center
                                               justify-center
                                               rounded-full
                                               bg-green-600
                                               text-white
                                               text-[11px]
                                               font-semibold"
                                    >

                                        {{ $conversation->unread_count > 99
                                            ? '99+'
                                            : $conversation->unread_count }}

                                    </span>

                                @endif

                            </div>


                            {{-- WhatsApp Account --}}

                            @if($conversation->whatsappAccount)

                                <div class="text-[11px] text-slate-400 mt-1 truncate">

                                    {{ $conversation->whatsappAccount->name }}

                                </div>

                            @endif

                        </div>

                    </div>

                </button>

            @empty

                <div class="p-8 text-center">

                    <div
                        class="w-12 h-12 mx-auto rounded-full
                               bg-slate-100
                               flex items-center justify-center
                               text-xl mb-3"
                    >
                        💬
                    </div>

                    <div class="text-sm font-medium text-slate-600">
                        No conversations found
                    </div>

                    @if($search)

                        <div class="text-xs text-slate-400 mt-1">
                            Try a different search.
                        </div>

                    @endif

                </div>

            @endforelse

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($conversations->hasPages())

            <div class="shrink-0 border-t bg-white p-3">

                {{ $conversations->links() }}

            </div>

        @endif

    </div>

</div>