<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Templates</h1>
            <p class="text-slate-500 mt-2">Create reusable WhatsApp message templates.</p>
        </div>

        <button wire:click="$set('showCreateModal', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition">
            + Create Template
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Total Templates</div>
            <div class="text-3xl font-bold mt-2">{{ $templates->count() }}</div>
        </div>
    </div>

    {{-- Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-2xl p-8">
                <h2 class="text-2xl font-bold mb-6">Create Template</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Template Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Appointment Reminder">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">Category</label>
                        <select wire:model="category" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            <option value="marketing">Marketing</option>
                            <option value="utility">Utility</option>
                            <option value="authentication">Authentication</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">Message Body</label>
                        <textarea wire:model="body" rows="8" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Hello @{{name}}, your appointment is scheduled for @{{appointment_date}}."></textarea>

                        <div class="mt-2 text-xs text-slate-500">
                            Available placeholders: @{{name}}, @{{phone}}, @{{email}}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200">
                        Cancel
                    </button>

                    <button wire:click="createTemplate" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">
                        Save Template
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Templates Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Category</th>
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Preview</th>
                </tr>
            </thead>

            <tbody>
                @forelse($templates as $template)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="p-4 font-medium text-slate-900">{{ $template->name }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                {{ ucfirst($template->category) }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-600 max-w-md truncate">{{ $template->body }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-12 text-center text-slate-400">No templates created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
