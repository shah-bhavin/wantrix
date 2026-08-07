<div class="h-full flex flex-col">
    <div wire:poll.3s class="flex flex-col h-full min-h-0">

        {{-- Search --}}
        <div class="shrink-0 p-4 border-b bg-white">

            <input wire:model.live.debounce.500ms="search" placeholder="Search..."
                class="w-full rounded-xl border-slate-300">

        </div>


        {{-- Conversation List --}}
        <div class="flex-1 min-h-0 overflow-y-auto">

            @foreach($conversations as $conversation)

            <button wire:key="conversation-{{ $conversation->id }}"
                wire:click="selectConversation({{ $conversation->id }})"
                @class([ 'w-full text-left border-b p-4 transition' , 'bg-amber-50'=>
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


                @if($conversation->unread_count > 0)

                <div class="mt-2">

                    <span class="inline-flex items-center justify-center
                                   min-w-6 h-6
                                   bg-green-600 text-white
                                   rounded-full px-2
                                   text-xs font-medium">
                        {{ $conversation->unread_count }}
                    </span>

                </div>

                @endif

            </button>

            @endforeach

        </div>

    </div>
</div>