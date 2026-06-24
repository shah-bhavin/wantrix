<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">Tags</h1>
            <p class="text-slate-500 mt-2">Organize contacts using tags.</p>
        </div>

        <button wire:click="$set('showCreateModal', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl transition-colors">
            + Create Tag
        </button>
    </div>

    @if($showCreateModal)
    <div class="fixed inset-0 bg-black/40 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-lg">
                <h2 class="text-2xl font-bold mb-6">Create Tag</h2>

                <div class="space-y-4">
                    <input type="text" wire:model="name" placeholder="Tag Name" class="w-full rounded-xl border-slate-300">

                    <select wire:model="color" class="w-full rounded-xl border-slate-300">
                        <option value="amber">Amber</option>
                        <option value="green">Green</option>
                        <option value="blue">Blue</option>
                        <option value="red">Red</option>
                        <option value="purple">Purple</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-slate-600 hover:text-slate-800">
                        Cancel
                    </button>
                    <button wire:click="createTag" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-colors">
                        Save Tag
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm">
                    <th class="p-4 text-left font-semibold">Tag</th>
                    <th class="p-4 text-left font-semibold">Color</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($tags as $tag)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-medium text-slate-900">
                            {{ $tag->name }}
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ ucfirst($tag->color) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-10 text-center text-slate-500">
                            No tags found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>