<div>
    @if($open)

    <div class="fixed inset-0 z-50">

        {{-- Background --}}
        <div wire:click="close" class="absolute inset-0 bg-black/40">
        </div>

        {{-- Drawer --}}
        <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl overflow-y-auto">

            <div class="flex items-center justify-between border-b p-6">

                <h2 class="text-xl font-bold">
                    Message Details
                </h2>

                <button wire:click="close" class="text-slate-500 hover:text-red-500">

                    ✕

                </button>

            </div>

            @if($message)

            <div class="p-6 space-y-6">

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Contact

                    </div>

                    <div class="font-semibold">

                        {{ $message->contact->name }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Phone

                    </div>

                    <div>

                        {{ $message->contact->phone_number }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500 mb-2">
                        Status
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
        {{ $message->status->badgeColor() }}">

                        {{ $message->status->label() }}

                    </span>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Message

                    </div>

                    <div class="whitespace-pre-wrap">

                        {{ $message->body }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Provider Message ID

                    </div>

                    <div>

                        {{ $message->provider_message_id ?? '-' }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Failure Reason

                    </div>

                    <div>

                        {{ $message->failure_reason ?? '-' }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500 mb-1">
                        Retry Count
                    </div>

                    <div class="font-medium text-slate-800">
                        {{ $message->retry_count ?? 0 }}
                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500 mb-1">
                        Last Retried At
                    </div>

                    <div class="text-slate-700">
                        {{ $message->last_retried_at?->format('d M Y h:i A') ?? '-' }}
                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Sent At

                    </div>

                    <div>

                        {{ $message->sent_at?->format('d M Y h:i A') ?? '-' }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Delivered At

                    </div>

                    <div>

                        {{ $message->delivered_at?->format('d M Y h:i A') ?? '-' }}

                    </div>

                </div>

                <div>

                    <div class="text-xs uppercase text-slate-500">

                        Read At

                    </div>

                    <div>

                        {{ $message->read_at?->format('d M Y h:i A') ?? '-' }}

                    </div>

                </div>

                <div class="border-t pt-6">

                    <div class="text-sm font-semibold text-slate-800 mb-4">
                        Message Status Timeline
                    </div>

                    @if($message->statusHistories->isEmpty())

                    <div class="text-sm text-slate-500">
                        No status history available.
                    </div>

                    @else

                    <div class="relative ml-2">

                        {{-- Vertical Line --}}
                        <div class="absolute left-2 top-2 bottom-2 w-px bg-slate-200"></div>

                        <div class="space-y-6">

                            @foreach($message->statusHistories as $history)

                            <div class="relative flex gap-4">

                                {{-- Status Dot --}}
                                <div class="relative z-10 w-4 h-4 mt-1 rounded-full bg-amber-500 ring-4 ring-white">
                                </div>

                                {{-- Status Details --}}
                                <div class="flex-1">

                                    <div class="flex items-center justify-between gap-4">

                                        <div class="text-sm font-semibold text-slate-800">

                                            {{ $history->status->label() }}

                                        </div>

                                        <div class="text-xs text-slate-500 whitespace-nowrap">

                                            {{ $history->changed_at?->format('d M Y h:i A') }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    @endif

                </div>

            </div>

            @endif

        </div>

    </div>

    @endif
</div>