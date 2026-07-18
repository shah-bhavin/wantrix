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
                        Message Timeline
                    </div>

                    <div class="space-y-4">

                        {{-- Created --}}
                        <div class="flex gap-3">

                            <div class="w-2 h-2 mt-2 rounded-full bg-slate-400"></div>

                            <div>

                                <div class="text-sm font-medium text-slate-800">
                                    Message Created
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $message->created_at?->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                        {{-- Sent --}}
                        @if($message->sent_at)

                        <div class="flex gap-3">

                            <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>

                            <div>

                                <div class="text-sm font-medium text-slate-800">
                                    Message Sent
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $message->sent_at->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                        @endif

                        {{-- Delivered --}}
                        @if($message->delivered_at)

                        <div class="flex gap-3">

                            <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></div>

                            <div>

                                <div class="text-sm font-medium text-slate-800">
                                    Message Delivered
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $message->delivered_at->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                        @endif

                        {{-- Read --}}
                        @if($message->read_at)

                        <div class="flex gap-3">

                            <div class="w-2 h-2 mt-2 rounded-full bg-purple-500"></div>

                            <div>

                                <div class="text-sm font-medium text-slate-800">
                                    Message Read
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $message->read_at->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                        @endif

                        {{-- Failed --}}
                        @if($message->status === \App\Enums\MessageStatus::FAILED)

                        <div class="flex gap-3">

                            <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>

                            <div>

                                <div class="text-sm font-medium text-red-700">
                                    Message Failed
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $message->updated_at?->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                        @endif

                    </div>

                </div>

            </div>

            @endif

        </div>

    </div>

    @endif
</div>