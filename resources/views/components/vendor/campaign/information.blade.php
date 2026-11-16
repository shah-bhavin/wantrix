<div class="grid md:grid-cols-3 gap-6 mt-8">

    {{-- Audience --}}
    <div>
        <div class="text-sm text-slate-500">Audience</div>

        <div class="text-xl font-bold">
            {{ $campaign->group?->contacts()->count() ?? 0 }}
        </div>
    </div>

    {{-- Group --}}
    <div>
        <div class="text-sm text-slate-500">Group</div>

        <div class="font-semibold">
            {{ $campaign->group?->name ?? '—' }}
        </div>
    </div>

    {{-- Message Source --}}
    <div>
        <div class="text-sm text-slate-500">
            {{ $campaign->template ? 'Template' : 'Message Type' }}
        </div>

        <div class="font-semibold">

            @if ($campaign->template)
                {{ $campaign->template->name }}

            @elseif ($campaign->whatsappTemplate)
                {{ $campaign->whatsappTemplate->name }}

            @elseif ($campaign->message_type)
                {{ ucfirst(
                    $campaign->message_type instanceof \App\Enums\MessageType
                        ? $campaign->message_type->value
                        : $campaign->message_type
                ) }}

            @else
                Direct Message
            @endif

        </div>
    </div>

</div>