
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Campaigns</h1>
            <p class="text-slate-500 mt-2">Create and manage WhatsApp template campaigns.</p>
        </div>

        <button
            wire:click="$set('showCreateModal', true)"
            class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition-colors"
        >
            + Create Campaign
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-5 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Total Campaigns</div>
            <div class="text-3xl font-bold mt-2">{{ $campaigns->count() }}</div>
        </div>
    </div>

    @if ($showCreateModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-6 sm:p-8 my-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Create Campaign</h2>

                    <button
                        type="button"
                        wire:click="$set('showCreateModal', false)"
                        class="text-slate-400 hover:text-slate-700 text-2xl"
                    >
                        &times;
                    </button>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Campaign Name
                        </label>

                        <input
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Diwali Offer Campaign"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Select Contact Group
                        </label>

                        <select
                            wire:model="group_id"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                            <option value="">Select Group</option>

                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('group_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            WhatsApp Account
                        </label>

                        <select
                            wire:model.live="whatsapp_account_id"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                            <option value="">Select WhatsApp Number</option>

                            @foreach ($whatsappAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->name }} ({{ $account->phone_number }})
                                </option>
                            @endforeach
                        </select>

                        @error('whatsapp_account_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Approved WhatsApp Template
                        </label>

                        <select
                            wire:model="whatsapp_template_id"
                            @disabled($whatsapp_account_id === '')
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 disabled:bg-slate-100"
                        >
                            <option value="">Select Approved Template</option>

                            @foreach ($whatsappTemplates as $template)
                                <option value="{{ $template->id }}">
                                    {{ $template->name }}
                                    ({{ $template->language }})
                                </option>
                            @endforeach
                        </select>

                        @error('whatsapp_template_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($whatsapp_account_id === '')
                            <p class="mt-1 text-xs text-slate-500">
                                First select a WhatsApp Account to load its approved templates.
                            </p>
                        @elseif ($whatsappTemplates->isEmpty())
                            <p class="mt-1 text-xs text-amber-700">
                                No approved templates found for this WhatsApp Account.
                                Sync templates from the WhatsApp Templates page.
                            </p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">
                                Only Meta-approved templates linked to this WhatsApp Account are shown.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Delay Between Messages
                        </label>

                        <div class="relative">
                            <input
                                type="number"
                                min="0"
                                max="3600"
                                wire:model="message_delay_seconds"
                                class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                                seconds
                            </span>
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            0 = no additional delay. Example: 5 = wait 5 seconds between messages.
                        </p>

                        @error('message_delay_seconds')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Schedule Campaign (Optional)
                        </label>

                        <input
                            type="datetime-local"
                            wire:model="scheduled_at"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-slate-500">
                            Leave empty to create the campaign as a draft.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8">
                    <button
                        type="button"
                        wire:click="$set('showCreateModal', false)"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        wire:click="createCampaign"
                        wire:loading.attr="disabled"
                        wire:target="createCampaign"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-colors disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="createCampaign">
                            Save Campaign
                        </span>
                        <span wire:loading wire:target="createCampaign">
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                        <th class="p-4 text-left">Campaign</th>
                        <th class="p-4 text-left">Group</th>
                        <th class="p-4 text-left">WhatsApp Template</th>
                        <th class="p-4 text-left">WhatsApp Account</th>
                        <th class="p-4 text-left">Scheduled</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($campaigns as $campaign)
                        @php
                            $statusValue = $campaign->status?->value ?? 'draft';

                            $colors = [
                                'draft' => 'bg-slate-100 text-slate-700',
                                'scheduled' => 'bg-blue-100 text-blue-700',
                                'processing' => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'failed' => 'bg-red-100 text-red-700',
                                'paused' => 'bg-orange-100 text-orange-700',
                                'cancelled' => 'bg-slate-100 text-slate-700',
                            ];

                            $templateName = $campaign->whatsappTemplate?->name
                                ?? $campaign->template?->name
                                ?? '—';
                        @endphp

                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-medium text-slate-900">
                                {{ $campaign->name }}
                            </td>

                            <td class="p-4 text-slate-600">
                                {{ $campaign->group?->name ?? '—' }}
                            </td>

                            <td class="p-4 text-slate-600">
                                {{ $templateName }}
                            </td>

                            <td class="p-4 text-slate-600">
                                {{ $campaign->whatsappAccount?->name ?? '—' }}
                            </td>

                            <td class="p-4 text-slate-600 whitespace-nowrap">
                                {{ $campaign->scheduled_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$statusValue] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ method_exists($campaign->status, 'label') ? $campaign->status->label() : ucfirst($statusValue) }}
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
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                No campaigns created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
