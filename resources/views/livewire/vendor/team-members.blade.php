<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Team Members</h1>
            <p class="text-slate-500 mt-1">Manage your team and access roles.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium shadow-sm transition">
            Add Member
        </button>
    </div>

    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-xl">
                    <h2 class="text-2xl font-bold mb-6">Add Team Member</h2>

                    <div class="space-y-4">
                        <div>
                            <input type="text" wire:model="name" placeholder="Full Name" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <input type="email" wire:model="email" placeholder="Email Address" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <select wire:model="role" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                                <option value="manager">Manager</option>
                                <option value="agent">Agent</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600">Cancel</button>
                        <button wire:click="createMember" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">Save Member</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20 7.9 21 9 21H15C16.1 21 16.9 20 17 19L18 7M9 7V4C9 3.4 9.4 3 10 3H14C14.6 3 15 3.4 15 4V7"></path>
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-slate-900">Delete Member?</h2>
                <p class="mt-3 text-slate-500">This action cannot be undone.</p>
            </div>

            <div class="flex justify-center gap-3 mt-8">
                <button wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200">Cancel</button>
                <button wire:click="deleteMember" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white">Delete</button>
            </div>
        </div>
    </div>
    @endif


    {{-- Members Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Role</th>
                    <th class="p-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                        <td class="p-4 font-medium text-slate-900">{{ $member->name }}</td>
                        <td class="p-4 text-slate-600">{{ $member->email }}</td>
                        <td class="p-4">
                            @if($member->role === 'owner')
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">Owner</span>
                            @elseif($member->role === 'manager')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">Manager</span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-medium">Agent</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="editMember({{ $member->id }})" class="px-3 py-2 rounded-xl text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100">
                                    Edit
                                </button>

                                @if($member->role !== 'owner')
                                    <button wire:click="confirmDelete({{ $member->id }})" class="px-3 py-2 rounded-xl text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-12 text-center text-slate-400">No team members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
