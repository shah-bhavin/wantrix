<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Groups</h1>
            <p class="text-slate-500 mt-2">Organize contacts into audiences for campaigns.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition-colors">
            + Create Group
        </button>
    </div>

    {{-- Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Create Group</h2>
                <div class="space-y-4">
                    <div>
                        <input type="text" wire:model="name" placeholder="Group Name" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    <div>
                        <textarea wire:model="description" rows="4" placeholder="Description (optional)" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="createGroup" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                        Save Group
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-slate-500 text-sm">Total Groups</div>
            <div class="text-3xl font-bold mt-2">{{ $groups->count() }}</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="p-4 text-left text-xs uppercase tracking-wider text-slate-500 font-semibold">Group Name</th>
                    <th class="p-4 text-left text-xs uppercase tracking-wider text-slate-500 font-semibold">Description</th>
                    <th class="p-4 text-left text-xs uppercase tracking-wider text-slate-500 font-semibold">Contacts</th>
                    <th class="p-4 text-left text-xs uppercase tracking-wider text-slate-500 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($groups as $group)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-medium text-slate-900">{{ $group->name }}</td>
                        <td class="p-4 text-slate-600">{{ $group->description ?: '-' }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">
                                {{ $group->contacts_count ?? $group->contacts()->count() }}
                            </span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('vendor.groups.contacts', $group) }}" class="text-amber-600 hover:text-amber-700 font-medium transition-colors">
                                Manage Contacts
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400">No groups found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
