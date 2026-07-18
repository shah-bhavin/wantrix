<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Campaigns</h1>
            <p class="text-slate-500 mt-2">Create and manage WhatsApp campaigns.</p>
        </div>

        <button wire:click="$set('showCreateModal', true)"
            class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition-colors">
            + Create Campaign
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Total Campaigns</div>
            <div class="text-3xl font-bold mt-2">{{ $campaigns->count() }}</div>
        </div>
    </div>

    @if($showCreateModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-8">
            <h2 class="text-2xl font-bold mb-6">Create Campaign</h2>

            <div class="space-y-4">
                <input type="text" wire:model="name" placeholder="Campaign Name"
                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">

                <select wire:model="group_id"
                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">Select Group</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>

                <select wire:model="template_id"
                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">Select Template</option>
                    @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>

                <select wire:model="whatsapp_account_id"
                    class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">Select WhatsApp Number</option>
                    @foreach($whatsappAccounts as $account)
                    <option value="{{ $account->id }}">
                        {{ $account->name }} ({{ $account->phone_number }})
                    </option>
                    @endforeach
                </select>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Delay Between Messages
                    </label>

                    <div class="relative">
                        <input type="number" min="0" max="3600" wire:model.live="message_delay_seconds"
                            class="w-full rounded-xl border-slate-300">

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                            seconds
                        </span>
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        0 = send immediately. Example: 5 = wait 5 seconds between messages.
                    </p>

                    @error('message_delay_seconds')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <input type="datetime-local" wire:model="scheduled_at" min="{{ now()->format('Y-m-d\TH:i') }}" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button wire:click="$set('showCreateModal', false)"
                    class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="createCampaign"
                    class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-colors">
                    Save Campaign
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                    <th class="p-4 text-left">Campaign</th>
                    <th class="p-4 text-left">Group</th>
                    <th class="p-4 text-left">Template</th>
                    <th class="p-4 text-left">Scheduled</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Action</th>
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
                        {{ $campaign->group->name }}
                    </td>
                    <td class="p-4 text-slate-600">
                        {{ $campaign->template->name }}
                    </td>
                    <td class="p-4 text-slate-600">
                        @if($campaign->scheduled_at)
                        {{ $campaign->scheduled_at->format('d M Y H:i') }}
                        @else
                        -
                        @endif
                    </td>

                    <td class="p-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$campaign->status->value] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $campaign->status->label() }}
                        </span>
                    </td>
                    <td class="p-4">
                        <a href="{{ route('vendor.campaigns.show', $campaign) }}">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-400">
                        No campaigns created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>



</div>