<div>
    @if($open)

    <div class="fixed inset-0 z-50">

        {{-- Background --}}
        <div
            wire:click="close"
            class="absolute inset-0 bg-black/40">
        </div>

        {{-- Drawer --}}
        <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl overflow-y-auto">

            <div class="flex items-center justify-between border-b p-6">

                <h2 class="text-xl font-bold">
                    Message Details
                </h2>

                <button
                    wire:click="close"
                    class="text-slate-500 hover:text-red-500">

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

                    <div class="text-xs uppercase text-slate-500">

                        Status

                    </div>

                    <div>

                        {{ $message->status->label() }}

                    </div>

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

            </div>

            @endif

        </div>

    </div>

    @endif
</div>
