<div class="h-full flex flex-col">

    <div wire:poll.3s class="flex flex-col h-full min-h-0">

        {{-- Account Selector --}}
        <div class="shrink-0 p-4 border-b bg-white">

            <label class="block text-sm font-medium text-slate-700 mb-2">
                WhatsApp Account
            </label>

            <select
                wire:model.live="selectedWhatsappAccountId"
                class="w-full rounded-xl border-slate-300"
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


        {{-- Search --}}
        <div class="shrink-0 p-4 border-b bg-white">

            <input
                wire:model.live.debounce.500ms="search"
                placeholder="Search..."
                class="w-full rounded-xl border-slate-300"
            >

        </div>


        {{-- Conversation List --}}
        <div class="flex-1 min-h-0 overflow-y-auto">

            @forelse($conversations as $conversation)

                <button
                    wire:key="conversation-{{ $conversation->id }}"
                    wire:click="selectConversation({{ $conversation->id }})"
                    @class([
                        'w-full text-left border-b p-4 transition',

                        'bg-amber-50' =>
                            $selectedConversationId == $conversation->id,

                        'hover:bg-slate-50' =>
                            $selectedConversationId != $conversation->id,
                    ])
                >

                    <div class="flex justify-between gap-3">

                        <div class="font-semibold truncate">

                            {{ $conversation->contact->name }}

                        </div>

                        @if($conversation->last_message_at)

                            <div class="text-xs text-slate-500 shrink-0">

                                {{ $conversation->last_message_at->format('H:i') }}

                            </div>

                        @endif

                    </div>


                    <div class="text-sm text-slate-500 mt-1">

                        {{ $conversation->contact->phone_number }}

                    </div>


                    {{-- WhatsApp Account --}}
                    @if($conversation->whatsappAccount)

                        <div class="text-xs text-slate-400 mt-1">

                            {{ $conversation->whatsappAccount->name }}

                        </div>

                    @else

                        <div class="text-xs text-amber-600 mt-1">

                            Legacy / Unknown WhatsApp Account

                        </div>

                    @endif


                    @if($conversation->unread_count > 0)

                        <div class="mt-2">

                            <span
                                class="inline-flex items-center justify-center
                                       min-w-6 h-6
                                       bg-green-600 text-white
                                       rounded-full px-2
                                       text-xs font-medium"
                            >
                                {{ $conversation->unread_count }}
                            </span>

                        </div>

                    @endif

                </button>

            @empty

                <div class="p-6 text-center text-sm text-slate-500">

                    No conversations found.

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($conversations->hasPages())

            <div class="shrink-0 border-t bg-white p-3">

                {{ $conversations->links() }}

            </div>

        @endif

    </div>

</div>