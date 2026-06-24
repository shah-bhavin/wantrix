<div>
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Contacts</h1>
                <p class="text-slate-500 mt-2">Manage your customer contacts.</p>
            </div>

            <button 
                wire:click="$set('showCreateModal', true)"
                class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium transition shadow-sm"
            >
                + Add Contact
            </button>
        </div>
    </div>


    @if($showCreateModal)
    <div class="fixed inset-0 bg-black/40 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">

            {{-- Modal Box --}}
            <div class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-xl">
                <h2 class="text-2xl font-bold mb-6 text-slate-900">Add Contact</h2>

                <div class="space-y-4">
                    <div>
                        <input type="text" wire:model="name" placeholder="Name"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('name') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div>
                        @enderror
                    </div>                    

                    {{-- Phone Input --}}
                    <div>
                        <input type="text" wire:model="phone_number" placeholder="Phone Number"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('phone_number') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Input --}}
                    <div>
                        <input type="email" wire:model="email" placeholder="Email"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('email') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Company Input --}}
                    <div>
                        <input type="text" wire:model="company" placeholder="Company"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('company') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div> @enderror
                    </div>

                    <select wire:model="status" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 text-sm text-slate-700">
                        @foreach(\App\Enums\ContactStatus::cases() as $status)
                            <option value="{{ $status->value }}">
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showCreateModal', false)"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium transition">
                        Cancel
                    </button>

                    <button wire:click="createContact" wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-medium transition shadow-sm inline-flex items-center gap-2">
                        <span wire:loading.remove wire:target="createContact">Save Contact</span>
                        <span wire:loading wire:target="createContact" class="inline-flex items-center gap-1.5">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>

            </div>

        </div>
    </div>
    @endif

    @if($showEditModal)
    <div class="fixed inset-0 bg-black/40 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">

            {{-- Modal Box --}}
            <div class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-xl">
                <h2 class="text-2xl font-bold mb-6 text-slate-900">Add Contact</h2>

                <div class="space-y-4">
                    <div>
                        <input type="text" wire:model="name" placeholder="Name"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('name') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div>
                        @enderror
                    </div>                    

                    {{-- Phone Input --}}
                    <div>
                        <input type="text" wire:model="phone_number" placeholder="Phone Number"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('phone_number') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Input --}}
                    <div>
                        <input type="email" wire:model="email" placeholder="Email"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('email') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Company Input --}}
                    <div>
                        <input type="text" wire:model="company" placeholder="Company"
                            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        @error('company') <div class="text-red-500 text-xs mt-1 px-1">{{ $message }}</div> @enderror
                    </div>

                    <select wire:model="status" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 text-sm text-slate-700">
                        @foreach(\App\Enums\ContactStatus::cases() as $status)
                            <option value="{{ $status->value }}">
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showCreateModal', false)"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium transition">
                        Cancel
                    </button>

                    <button wire:click="updateContact" wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-medium transition shadow-sm inline-flex items-center gap-2">
                        <span wire:loading.remove wire:target="createContact">Save Contact</span>
                        <span wire:loading wire:target="createContact" class="inline-flex items-center gap-1.5">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>

            </div>

        </div>
    </div>
    @endif

    @if($deletingContactId)
    <div class="fixed inset-0 bg-black/40 z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-3xl p-8 max-w-md w-full">
                <h2 class="text-xl font-bold">Delete Contact</h2>
                <p class="mt-4 text-slate-500">Are you sure?</p>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('deletingContactId', null)" class="px-4 py-2 text-slate-600 hover:text-slate-800">
                        Cancel
                    </button>
                    <button wire:click="deleteContact" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="flex flex-row gap-4 flex-wrap">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex-1 min-w-[200px]">
            <div class="text-sm font-medium text-slate-500">Total Contacts</div>
            <div class="text-3xl font-bold mt-2 text-slate-900">{{ $totalContacts }}</div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex-1 min-w-[200px]">
            <div class="text-sm font-medium text-slate-500">Active Contacts</div>
            <div class="text-3xl font-bold mt-2 text-slate-900">{{ $activeContacts }}</div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex-1 min-w-[200px]">
            <div class="text-sm font-medium text-slate-500">Blocked Contacts</div>
            <div class="text-3xl font-bold mt-2 text-slate-900">{{ $blockedContacts }}</div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex-1 min-w-[200px]">
            <div class="text-sm font-medium text-slate-500">Unsubscribed Contacts</div>
            <div class="text-3xl font-bold mt-2 text-slate-900">{{ $unsubscribedContacts }}</div>
        </div>
    </div>
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search contacts by name, phone, email..."
            class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 text-sm text-slate-700"
        >
    </div>


    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm mt-2">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                    <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Phone</th>
                    <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                    <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Company</th>
                    <th class="p-4 text-xs font-semibold uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($contacts as $contact)
                <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition text-sm text-slate-700">
                    <td class="p-4 font-medium text-slate-900">
                        {{ $contact->name }}
                    </td>
                    <td class="p-4 text-slate-600">
                        {{ $contact->phone_number }}
                    </td>
                    <td class="p-4 text-slate-500">
                        {{ $contact->email ?? '-' }}
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        @if($contact->status === 'active')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium border border-green-200">
                                Active
                            </span>
                        @elseif($contact->status === 'blocked')
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium border border-red-200">
                                Blocked
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                                Unsubscribed
                            </span>
                        @endif
                    </td>

                    <td class="p-4 text-slate-600">
                        {{ $contact->company ?? '-' }}
                    </td>
                    
                    <td class="p-4">
                        <div class="flex gap-2">
                            <button wire:click="editContact({{ $contact->id }})" class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs">
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $contact->id }})" class="px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs">
                                Delete
                            </button>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-400 italic">
                        No contacts found.
                    </td>
                </tr>
            @endforelse
        </tbody>

        </table>
        <div class="p-4 border-t border-slate-200">
            {{ $contacts->links() }}
        </div>
    </div>
</div>