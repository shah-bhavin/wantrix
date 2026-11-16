<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Campaigns
            </h1>

            <p class="text-slate-500 mt-2">
                Create and manage WhatsApp campaigns.
            </p>
        </div>

        <button
            wire:click="$set('showCreateModal', true)"
            class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition-colors"
        >
            + Create Campaign
        </button>
    </div>


    @if (session()->has('success'))

        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    @if (session()->has('error'))

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>

    @endif


    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">
                Total Campaigns
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ $campaigns->count() }}
            </div>
        </div>

    </div>


    @if($showCreateModal)

        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4 overflow-y-auto">

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-8 my-8">

                <div class="flex justify-between items-center mb-6">

                    <h2 class="text-2xl font-bold">
                        Create Campaign
                    </h2>

                    <button
                        wire:click="$set('showCreateModal', false)"
                        class="text-slate-400 hover:text-slate-700 text-xl"
                    >
                        ×
                    </button>

                </div>


                <div class="space-y-5">


                    {{-- Campaign Name --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Campaign Name
                        </label>

                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Enter campaign name"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Group --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Select Group
                        </label>

                        <select
                            wire:model="group_id"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                            <option value="">
                                Select Group
                            </option>

                            @foreach($groups as $group)

                                <option value="{{ $group->id }}">
                                    {{ $group->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('group_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- WhatsApp Account --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            WhatsApp Number
                        </label>

                        <select
                            wire:model.live="whatsapp_account_id"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                            <option value="">
                                Select WhatsApp Number
                            </option>

                            @foreach($whatsappAccounts as $account)

                                <option value="{{ $account->id }}">
                                    {{ $account->name }}
                                    ({{ $account->phone_number }})
                                </option>

                            @endforeach

                        </select>

                        @error('whatsapp_account_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Message Type --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Message Type
                        </label>

                        <select
                            wire:model.live="message_type"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                            <option value="template">
                                Meta Template
                            </option>

                            <option value="text">
                                Text Message
                            </option>

                            <option value="image">
                                Image
                            </option>

                            <option value="video">
                                Video
                            </option>

                            <option value="document">
                                Document
                            </option>

                            <option value="audio">
                                Audio
                            </option>

                        </select>

                    </div>


                    {{-- META TEMPLATE --}}
                    @if($message_type === 'template')

                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Approved Meta Template
                            </label>

                            @if(!$whatsapp_account_id)

                                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-700">
                                    Please select a WhatsApp number first.
                                </div>

                            @else

                                <select
                                    wire:model="whatsapp_template_id"
                                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                                >

                                    <option value="">
                                        Select Approved Template
                                    </option>

                                    @foreach($whatsappTemplates as $template)

                                        <option value="{{ $template->id }}">
                                            {{ $template->name }}
                                        </option>

                                    @endforeach

                                </select>

                                @if($whatsappTemplates->isEmpty())

                                    <p class="mt-2 text-xs text-slate-500">
                                        No approved Meta templates found for this WhatsApp account.
                                    </p>

                                @endif

                            @endif

                            @error('whatsapp_template_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    @endif


                    {{-- TEXT MESSAGE --}}
                    @if($message_type === 'text')

                        <div class="space-y-4">

                            <div>

                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Text Message Source
                                </label>

                                <select
                                    wire:model.live="text_content_mode"
                                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                                >

                                    <option value="direct">
                                        Direct Message
                                    </option>

                                    <option value="template">
                                        Local Template
                                    </option>

                                </select>

                            </div>


                            {{-- Direct Text --}}
                            @if($text_content_mode === 'direct')

                                <div>

                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        Message
                                    </label>

                                    <textarea
                                        wire:model="message_body"
                                        rows="6"
                                        placeholder="Write your message here..."
                                        class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                                    ></textarea>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Use this option for a custom message.
                                    </p>

                                    @error('message_body')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            @endif


                            {{-- Local Template --}}
                            @if($text_content_mode === 'template')

                                <div>

                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        Local Template
                                    </label>

                                    <select
                                        wire:model="template_id"
                                        class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                                    >

                                        <option value="">
                                            Select Local Template
                                        </option>

                                        @foreach($templates as $template)

                                            <option value="{{ $template->id }}">
                                                {{ $template->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('template_id')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            @endif

                        </div>

                    @endif


                    {{-- MEDIA MESSAGE --}}
                    @if(in_array($message_type, [
                        'image',
                        'video',
                        'document',
                        'audio'
                    ]))

                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Select Media
                            </label>

                            <select
                                wire:model="media_library_id"
                                class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                            >

                                <option value="">
                                    Select {{ ucfirst($message_type) }}
                                </option>

                                @foreach($media as $item)

                                    @if($item->type->value === $message_type)

                                        <option value="{{ $item->id }}">
                                            {{ $item->original_name }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                            @error('media_library_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    @endif


                    {{-- Delay --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Delay Between Messages
                        </label>

                        <div class="relative">

                            <input
                                type="number"
                                min="0"
                                max="3600"
                                wire:model.live="message_delay_seconds"
                                class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                                seconds
                            </span>

                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            0 = no delay. Example: 5 = wait 5 seconds between messages.
                        </p>

                        @error('message_delay_seconds')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Schedule --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Schedule Campaign
                            <span class="text-slate-400 font-normal">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="datetime-local"
                            wire:model="scheduled_at"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Leave empty to save as a draft.
                        </p>

                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                </div>


                <div class="flex justify-end gap-3 mt-8">

                    <button
                        wire:click="$set('showCreateModal', false)"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </button>

                    <button
                        wire:click="createCampaign"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-colors disabled:opacity-50"
                    >
                        <span wire:loading.remove>
                            Save Campaign
                        </span>

                        <span wire:loading>
                            Saving...
                        </span>
                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- Campaign List --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">

                        <th class="p-4 text-left">
                            Campaign
                        </th>

                        <th class="p-4 text-left">
                            Group
                        </th>

                        <th class="p-4 text-left">
                            Message Type
                        </th>

                        <th class="p-4 text-left">
                            Scheduled
                        </th>

                        <th class="p-4 text-left">
                            Status
                        </th>

                        <th class="p-4 text-left">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($campaigns as $campaign)

                        @php

                            $colors = [

                                'draft' => 'bg-slate-100 text-slate-700',

                                'scheduled' => 'bg-blue-100 text-blue-700',

                                'processing' => 'bg-amber-100 text-amber-700',

                                'completed' => 'bg-green-100 text-green-700',

                                'failed' => 'bg-red-100 text-red-700',

                            ];

                        @endphp


                        <tr class="hover:bg-slate-50/50 transition-colors">

                            <td class="p-4 font-medium text-slate-900">
                                {{ $campaign->name }}
                            </td>


                            <td class="p-4 text-slate-600">
                                {{ $campaign->group?->name ?? '-' }}
                            </td>


                            <td class="p-4 text-slate-600">

                                @if($campaign->message_type)

                                    {{ ucfirst($campaign->message_type->value ?? $campaign->message_type) }}

                                @else

                                    -

                                @endif

                            </td>


                            <td class="p-4 text-slate-600">

                                @if($campaign->scheduled_at)

                                    {{ $campaign->scheduled_at->format('d M Y H:i') }}

                                @else

                                    Draft

                                @endif

                            </td>


                            <td class="p-4">

                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$campaign->status->value] ?? 'bg-slate-100 text-slate-700' }}"
                                >
                                    {{ $campaign->status->label() }}
                                </span>

                            </td>


                            <td class="p-4">

                                <a
                                    href="{{ route('vendor.campaigns.show', $campaign) }}"
                                    class="text-amber-600 hover:text-amber-700 font-medium"
                                >
                                    View
                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="p-12 text-center text-slate-400"
                            >
                                No campaigns created yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>