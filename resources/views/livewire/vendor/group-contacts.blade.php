<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">{{ $group->name }}</h1>
        <p class="text-slate-500 mt-2">Assign contacts to this group.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($contacts as $contact)
                <label class="flex items-center gap-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" value="{{ $contact->id }}" wire:model="selectedContacts" class="rounded text-amber-500 focus:ring-amber-500 border-slate-300">
                    <div>
                        <div class="font-medium text-slate-900">{{ $contact->name }}</div>
                        <div class="text-sm text-slate-500">{{ $contact->phone_number }}</div>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="mt-8 flex justify-end">
            <button wire:click="save" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                Save Contacts
            </button>
        </div>
    </div>
</div>
