<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">WhatsApp Accounts</h1>
            <p class="text-slate-500 mt-2">Manage WhatsApp Business Accounts</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium">Add Account</button>
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-8">
                <h2 class="text-2xl font-bold mb-6">Add WhatsApp Account</h2>
                <div class="space-y-4">
                    <div>
                        <input type="text" wire:model="name" placeholder="Account Name" class="w-full rounded-xl border-slate-300">
                        @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="phone_number" placeholder="Phone Number" class="w-full rounded-xl border-slate-300">
                        @error('phone_number') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <select wire:model="provider" class="w-full rounded-xl border-slate-300">
                            <option value="meta">Meta Cloud API</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" wire:model="phone_number_id" placeholder="Phone Number ID" class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <textarea wire:model="access_token" rows="4" placeholder="Access Token" class="w-full rounded-xl border-slate-300"></textarea>
                    </div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_active">
                        <span>Active Account</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 border rounded-xl">Cancel</button>
                    <button wire:click="createAccount" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl">Save Account</button>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b">
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Phone Number</th>
                    <th class="p-4 text-left">Provider</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr class="border-b">
                        <td class="p-4 font-medium">{{ $account->name }}</td>
                        <td class="p-4">{{ $account->phone_number }}</td>
                        <td class="p-4">{{ ucfirst($account->provider) }}</td>
                        <td class="p-4">
                            @if($account->is_active)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                            @else
                                <button wire:click="activateAccount({{ $account->id }})" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-lg text-xs">Activate</button>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="editAccount({{ $account->id }})" class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</button>
                                <button wire:click="confirmDelete({{ $account->id }})" class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-xl bg-red-50 text-red-700 hover:bg-red-100 transition">Delete</button>
                            </div>
                        </td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400">No WhatsApp Accounts Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20 7.9 21 9 21H15C16.1 21 16.9 20 17 19L18 7M9 7V4C9 3.4 9.4 3 10 3H14C14.6 3 15 3.4 15 4V7"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Delete Account?</h2>
                    <p class="mt-3 text-slate-500">This action cannot be undone.</p>
                </div>

                <div class="flex justify-center gap-3 mt-8">
                    <button wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200">Cancel</button>
                    <button wire:click="deleteAccount" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white">Delete</button>
                </div>
            </div>
        </div>
    @endif

</div>
